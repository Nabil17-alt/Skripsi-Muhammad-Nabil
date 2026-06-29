<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Address;
use App\Models\PaymentMethod;
use App\Models\Payment;
use App\Models\Installment;
use App\Services\MidtransService;
use Exception;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        $paymentMethods = PaymentMethod::where('is_active', true)
            ->whereNotIn('type', ['dana', 'ovo', 'shopeepay'])
            ->get();

        $cartTotal = 0;
        foreach ($cart as $item) {
            $line = $item['price'] * $item['quantity'];
            if (($item['design_option'] ?? null) === 'service') {
                $product = \App\Models\Product::find($item['product_id']);
                $designFee = $product ? $product->design_service_fee : ($item['design_service_fee'] ?? 0);
                $line += $designFee;
            }
            $cartTotal += $line;
        }

        if ($cartTotal <= config('installments.min_amount')) {
            $paymentMethods = $paymentMethods->filter(function ($method) {
                return $method->type !== 'installment';
            });
        }

        $allowedTenor = auth()->user()->allowed_tenor ?? 3;

        return view('frontend.checkout.index', compact('cart', 'paymentMethods', 'cartTotal', 'allowedTenor'));
    }

    public function process(Request $request, MidtransService $midtransService)
    {
        $request->validate([
            'customer_name' => 'required|string',
            'customer_phone' => 'required|string',
            'full_address' => 'required|string',
            'payment_method_id' => 'required|exists:tb_payment_methods,id',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index');
        }

        $paymentMethod = PaymentMethod::findOrFail($request->payment_method_id);
        \Log::info('CHECKOUT DEBUG: payment_method_id=' . $request->payment_method_id . ' type=' . $paymentMethod->type . ' name=' . $paymentMethod->name);

        $subtotal = 0;
        $designServiceTotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
            if (($item['design_option'] ?? null) === 'service') {
                $product = \App\Models\Product::find($item['product_id']);
                $designFee = $product ? $product->design_service_fee : ($item['design_service_fee'] ?? 0);
                $designServiceTotal += $designFee;
            }
        }

        // Distance Calculation
        $shippingDistanceKm = null;
        $shippingFee = 0;
        $deliveryMethod = 'ambil_di_toko';
        $userLat = $request->input('latitude');
        $userLng = $request->input('longitude');

        if ($userLat !== null && $userLng !== null) {
            $storeLat = 0.4077919;
            $storeLng = 101.8562836;

            $earthRadius = 6371;
            $dLat = deg2rad($storeLat - $userLat);
            $dLng = deg2rad($storeLng - $userLng);

            $a = sin($dLat / 2) * sin($dLat / 2)
                + cos(deg2rad($userLat)) * cos(deg2rad($storeLat))
                * sin($dLng / 2) * sin($dLng / 2);
            $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
            $shippingDistanceKm = round($earthRadius * $c, 2);

            if ($shippingDistanceKm <= 1) {
                $shippingFee = 0;
                $deliveryMethod = 'antar';
            } else {
                $shippingFee = 0;
                $deliveryMethod = 'ambil_di_toko';
            }
        }

        $baseTotal = $subtotal + $shippingFee + $designServiceTotal;

        // Installment Logic: Only if baseTotal > 1000000
        $installmentTenor = null;
        $installmentInterestFee = 0;
        $installmentMonthly = 0;
        $initialPaymentAmount = $baseTotal;
        $isInstallment = false;

        if ($paymentMethod->type === 'installment') {
            if ($baseTotal <= config('installments.min_amount')) {
                return back()->with('error', 'Fitur cicilan hanya tersedia untuk transaksi di atas Rp ' . number_format(config('installments.min_amount'), 0, ',', '.'));
            }
            
            $userAllowedTenor = auth()->user()->allowed_tenor ?? 3;
            $request->validate([
                'installment_tenor' => 'required|integer|in:' . $userAllowedTenor,
            ]);
            $isInstallment = true;
            $installmentTenor = $userAllowedTenor;

            // DP (Down Payment) berdasarkan persentase di config
            $dpPercent = config('installments.dp_percent') / 100;
            $initialPaymentAmount = ceil($baseTotal * $dpPercent);
            $remainingAmount = $baseTotal - $initialPaymentAmount;

            // Cicilan bulanan tanpa bunga (atau sesuaikan jika butuh bunga)
            $installmentMonthly = ceil($remainingAmount / $installmentTenor);
        }

        $userId = $request->user()->id;

        $address = Address::create([
            'user_id' => $userId,
            'label' => 'Alamat Utama',
            'recipient_name' => $request->customer_name,
            'phone' => $request->customer_phone,
            'full_address' => $request->full_address,
            'latitude' => $request->input('latitude'),
            'longitude' => $request->input('longitude'),
            'is_default' => true,
        ]);

        $order = Order::create([
            'user_id' => $userId,
            'order_number' => 'ORD-' . strtoupper(Str::random(8)),
            'shipping_address_id' => $address->id,
            'promo_id' => null,
            'subtotal_amount' => $subtotal,
            'discount_amount' => 0,
            'shipping_fee' => $shippingFee,
            'grand_total' => $baseTotal,
            'shipping_distance_km' => $shippingDistanceKm,
            'production_status' => 'Menunggu pembayaran',
            'payment_status' => 'pending',
            'delivery_method' => $deliveryMethod,
            'notes' => null,
        ]);

        foreach ($cart as $item) {
            $designFee = 0;
            if (($item['design_option'] ?? null) === 'service') {
                $product = \App\Models\Product::find($item['product_id']);
                $designFee = $product ? $product->design_service_fee : ($item['design_service_fee'] ?? 0);
            }

            $designFilePath = null;
            if (!empty($item['design_file'])) {
                $tempPath = $item['design_file'];
                if (\Storage::disk('public')->exists($tempPath)) {
                    $newPath = 'designs/' . basename($tempPath);
                    \Storage::disk('public')->move($tempPath, $newPath);
                    $designFilePath = $newPath;
                } else {
                    $designFilePath = $item['design_file'];
                }
            }

            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['product_id'],
                'product_variant_id' => null,
                'quantity' => $item['quantity'],
                'unit_price' => $item['price'],
                'design_service_fee' => $designFee,
                'notes' => $item['notes'] ?? null,
                'design_file_path' => $designFilePath,
            ]);
        }

        // Simpan referensi pembayaran
        $paymentReference = 'PAY-' . strtoupper(Str::random(10));

        $payment = Payment::create([
            'order_id' => $order->id,
            'payment_method_id' => $paymentMethod->id,
            'bank_account_id' => null,
            'amount' => $initialPaymentAmount,
            'installment_tenor' => $installmentTenor,
            'installment_interest_fee' => $installmentInterestFee,
            'installment_monthly_amount' => $installmentMonthly,
            'reference' => $paymentReference,
            'status' => 'belum_dibayar',
            'transaction_id_gateway' => null,
            'payment_proof_path' => null,
            'raw_callback_log' => null,
            'paid_at' => null,
        ]);

        if ($isInstallment) {
            $baseDueDate = now()->addMonthNoOverflow();
            $remaining = $baseTotal - $initialPaymentAmount;

            for ($i = 1; $i <= $installmentTenor; $i++) {
                $amount = $installmentMonthly;
                if ($i === $installmentTenor) {
                    $amount = $remaining; // Pembulatan di akhir
                }

                Installment::create([
                    'payment_id' => $payment->id,
                    'sequence' => $i,
                    'amount' => $amount,
                    'due_date' => $baseDueDate->copy()->addMonthsNoOverflow($i - 1)->toDateString(),
                    'status' => 'pending',
                ]);

                $remaining -= $amount;
            }
        }

        // Generate Midtrans Snap Token if method is QRIS/Ewallet
        if (in_array($paymentMethod->type, ['qris', 'ewallet', 'dana', 'ovo', 'shopeepay'])) {
            $transactionDetails = [
                'order_id' => $paymentReference,
                'gross_amount' => (int) $initialPaymentAmount,
            ];

            $customerDetails = [
                'first_name' => $request->customer_name,
                'email' => $request->user()->email,
                'phone' => $request->customer_phone,
            ];

            $itemDetails = [];
            if ($isInstallment) {
                $itemDetails[] = [
                    'id' => 'DP',
                    'price' => $initialPaymentAmount,
                    'quantity' => 1,
                    'name' => 'DP 50% Pesanan ' . $order->order_number,
                ];
            } else {
                foreach ($cart as $item) {
                    $itemDetails[] = [
                        'id' => $item['product_id'],
                        'price' => $item['price'],
                        'quantity' => $item['quantity'],
                        'name' => substr($item['name'], 0, 50),
                    ];
                }
                if ($designServiceTotal > 0) {
                    $itemDetails[] = [
                        'id' => 'DESIGN',
                        'price' => $designServiceTotal,
                        'quantity' => 1,
                        'name' => 'Biaya Jasa Desain',
                    ];
                }
            }

            try {
                // Map payment method type to Midtrans enabled_payments
                $enabled = [];
                switch ($paymentMethod->type) {
                    case 'qris':
                        $enabled = ['qris'];
                        break;
                    case 'ewallet':
                        $enabled = ['shopeepay', 'dana', 'ovo'];
                        break;
                    case 'dana':
                        $enabled = ['dana'];
                        break;
                    case 'ovo':
                        $enabled = ['ovo'];
                        break;
                    case 'shopeepay':
                        $enabled = ['shopeepay'];
                        break;
                    case 'installment':
                        $enabled = ['credit_card'];
                        break;
                    default:
                        $enabled = [];
                }

                $redirectUrl = route('payments.show', $order->order_number);
                $options = [
                    'callbacks' => [
                        'finish' => $redirectUrl,
                        'unfinish' => $redirectUrl,
                        'error' => $redirectUrl,
                    ],
                    'gopay' => [
                        'enable_callback' => true,
                        'callback_url' => $redirectUrl,
                    ],
                    'shopeepay' => [
                        'callback_url' => $redirectUrl,
                    ],
                    'dana' => [
                        'callback_url' => $redirectUrl,
                    ],
                ];
                if (!empty($enabled)) {
                    $options['enabled_payments'] = $enabled;
                }

                $itemTotal = 0;

                foreach ($itemDetails as $item) {
                    $itemTotal += ($item['price'] * $item['quantity']);
                }

                if ($itemTotal != $initialPaymentAmount) {
                    throw new \Exception(
                        'Midtrans item_details total mismatch.'
                    );
                }

                 if ($paymentMethod->type === 'qris') {
                    $options['enabled_payments'] = [
                        'gopay'
                    ];
                } elseif ($paymentMethod->type === 'dana') {
                    $options['enabled_payments'] = [
                        'dana'
                    ];
                } elseif ($paymentMethod->type === 'ovo') {
                    $options['enabled_payments'] = [
                        'ovo'
                    ];
                } elseif ($paymentMethod->type === 'shopeepay') {
                    $options['enabled_payments'] = [
                        'shopeepay'
                    ];
                } elseif ($paymentMethod->type === 'ewallet') {
                    $options['enabled_payments'] = [
                        'shopeepay',
                        'dana',
                        'ovo'
                    ];
                }

                // Filter out payment-method-specific callbacks if that payment method is not enabled
                $finalEnabled = $options['enabled_payments'] ?? [];
                if (!in_array('gopay', $finalEnabled)) {
                    unset($options['gopay']);
                }
                if (!in_array('shopeepay', $finalEnabled)) {
                    unset($options['shopeepay']);
                }
                if (!in_array('dana', $finalEnabled)) {
                    unset($options['dana']);
                }

                $snapToken = $midtransService->getSnapToken(
                    $transactionDetails,
                    $customerDetails,
                    $itemDetails,
                    $options
                );
                $payment->update(['snap_token' => $snapToken]);
            } catch (Exception $e) {
                \Log::error('Failed generating snap token: ' . $e->getMessage());
            }
        }

        session()->forget('cart');

        return redirect()->route('payments.show', $order->order_number);
    }
}
