<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use App\Models\BankAccount;
use App\Models\PaymentMethod;
use App\Models\Chat;
use App\Models\ChatMessage;
use App\Models\Installment;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Services\MidtransService;

class PaymentController extends Controller
{
    public function show(Request $request, string $orderNumber, MidtransService $midtransService)
    {
        $order = Order::where('order_number', $orderNumber)->firstOrFail();
        /** @var Payment|null $payment */
        $payment = Payment::with(['method', 'installments'])
            ->where('order_id', $order->id)
            ->orderByDesc('created_at')
            ->first();

        // 1. Instant check from URL query parameters (important for DANA redirects where the browser is redirected to a different site and back)
        $transactionStatus = $request->query('transaction_status');
        $statusCode = $request->query('status_code');
        $queryOrderId = $request->query('order_id');

        if ($payment && $queryOrderId === $payment->reference) {
            if (!in_array($payment->status, ['lunas', 'sudah_dibayar', 'sebagian_dibayar'])) {
                if (in_array($transactionStatus, ['settlement', 'capture']) || $statusCode == '200') {
                    $isInstallment = $payment->method->type === 'installment';
                    
                    $payment->update([
                        'status' => $isInstallment ? 'sebagian_dibayar' : 'lunas',
                        'paid_at' => now(),
                    ]);

                    $order->update([
                        'payment_status' => $isInstallment ? 'sebagian_dibayar' : 'lunas',
                        'production_status' => 'Menunggu Konfirmasi',
                    ]);

                    // Auto create chat
                    $chat = \App\Models\Chat::firstOrCreate([
                        'user_id' => $order->user_id,
                        'order_id' => null,
                    ]);

                    // Check if success message has already been created to avoid duplicates
                    $messageExists = \App\Models\ChatMessage::where('chat_id', $chat->id)
                        ->where('message', 'like', '%Pembayaran otomatis%')
                        ->exists();

                    if (!$messageExists) {
                        \App\Models\ChatMessage::create([
                            'chat_id' => $chat->id,
                            'sender_type' => 'admin',
                            'sender_id' => 1,
                            'message' => 'Pembayaran otomatis Anda telah kami terima. Pesanan sedang diproses. Silakan balas pesan ini jika ada diskusi terkait pesanan/desain.',
                        ]);

                        foreach ($order->items as $item) {
                            if ($item->design_file_path) {
                                \App\Models\ChatMessage::create([
                                    'chat_id' => $chat->id,
                                    'sender_type' => 'customer',
                                    'sender_id' => $order->user_id,
                                    'message' => 'Berikut adalah lampiran file desain untuk produk: ' . ($item->product->name ?? 'Custom'),
                                    'file_path' => $item->design_file_path,
                                ]);
                            }
                        }
                    }

                    // Refresh relations
                    $payment->load('method', 'installments');
                    $order->refresh();
                }
            }
        }

        // 2. Active status pulling from Midtrans API as a double fallback
        if ($payment && in_array($payment->method->type ?? null, ['qris', 'ewallet', 'dana', 'ovo', 'shopeepay'])) {
            if (!in_array($payment->status, ['lunas', 'sudah_dibayar', 'sebagian_dibayar'])) {
                $statusResult = $midtransService->getTransactionStatus($payment->reference);
                
                if ($statusResult) {
                    $transactionStatusApi = $statusResult->transaction_status ?? null;
                    
                    if (in_array($transactionStatusApi, ['settlement', 'capture'])) {
                        $isInstallment = $payment->method->type === 'installment';
                        
                        $payment->update([
                            'status' => $isInstallment ? 'sebagian_dibayar' : 'lunas',
                            'paid_at' => now(),
                        ]);

                        $order->update([
                            'payment_status' => $isInstallment ? 'sebagian_dibayar' : 'lunas',
                            'production_status' => 'Menunggu Konfirmasi',
                        ]);

                        // Auto create chat
                        $chat = \App\Models\Chat::firstOrCreate([
                            'user_id' => $order->user_id,
                            'order_id' => null,
                        ]);

                        // Check if success message has already been created to avoid duplicates
                        $messageExists = \App\Models\ChatMessage::where('chat_id', $chat->id)
                            ->where('message', 'like', '%Pembayaran otomatis%')
                            ->exists();

                        if (!$messageExists) {
                            \App\Models\ChatMessage::create([
                                'chat_id' => $chat->id,
                                'sender_type' => 'admin',
                                'sender_id' => 1,
                                'message' => 'Pembayaran Anda telah kami terima. Pesanan sedang diproses. Silakan balas pesan ini jika ada diskusi terkait pesanan/desain.',
                            ]);

                            foreach ($order->items as $item) {
                                if ($item->design_file_path) {
                                    \App\Models\ChatMessage::create([
                                        'chat_id' => $chat->id,
                                        'sender_type' => 'customer',
                                        'sender_id' => $order->user_id,
                                        'message' => 'Berikut adalah lampiran file desain untuk produk: ' . ($item->product->name ?? 'Custom'),
                                        'file_path' => $item->design_file_path,
                                    ]);
                                }
                            }
                        }

                        // Refresh relations
                        $payment->load('method', 'installments');
                        $order->refresh();
                    } elseif (in_array($transactionStatusApi, ['deny', 'expire', 'cancel'])) {
                        $payment->update([
                            'status' => 'gagal',
                        ]);
                        $order->update([
                            'payment_status' => 'gagal',
                        ]);
                        $payment->load('method', 'installments');
                        $order->refresh();
                    }
                }
            }
        }

        $channels = collect();

        if ($payment && $payment->payment_method_id) {
            $paymentMethodId = $payment->payment_method_id;
            
            // Both Transfer Bank and Cicilan (installment) use the exact same bank accounts
            if ($payment->method && $payment->method->type === 'installment') {
                $bankTransferMethod = PaymentMethod::where('type', 'bank_transfer')->first();
                if ($bankTransferMethod) {
                    $paymentMethodId = $bankTransferMethod->id;
                }
            }

            $channels = BankAccount::where('payment_method_id', $paymentMethodId)
                ->where('is_active', true)
                ->orderBy('bank_name')
                ->get();
        }

        return view('frontend.payments.show', compact('order', 'payment', 'channels'));
    }

    public function invoice(string $orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)->with(['user', 'items.product'])->firstOrFail();
        $payment = Payment::with(['method', 'installments'])
            ->where('order_id', $order->id)
            ->orderByDesc('created_at')
            ->first();

        return view('frontend.payments.invoice', compact('order', 'payment'));
    }

    public function uploadProof(Request $request, string $orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)->firstOrFail();
        $user = $request->user();

        /** @var Payment $payment */
        $payment = Payment::with('method')
            ->where('order_id', $order->id)
            ->orderByDesc('created_at')
            ->firstOrFail();

        $type = $payment->method->type ?? null;
        if (!in_array($type, ['bank_transfer', 'installment'])) {
            return redirect()
                ->route('payments.show', $order->order_number)
                ->with('error', 'Metode ini tidak memerlukan upload bukti pembayaran.');
        }

        $request->validate([
            'payment_proof' => 'required|file|mimes:jpg,jpeg,png,pdf|max:4096',
            'note' => 'nullable|string',
        ]);

        $request->validate([
            'payment_proof' => 'required|image|max:5120',
            'installment_id' => 'nullable|exists:tb_installments,id',
        ]);

        $path = $request->file('payment_proof')->store('payment_proofs', 'public');

        if ($request->installment_id) {
            $installment = Installment::findOrFail($request->installment_id);
            $installment->update([
                'payment_proof_path' => $path,
                'status' => 'menunggu_konfirmasi',
                'notes' => $request->note
            ]);
            $messageText = 'Bukti pembayaran Cicilan ke-' . $installment->sequence . ' untuk pesanan ' . $order->order_number . '.';
        } else {
            $payment->update([
                'payment_proof_path' => $path,
                'status' => 'menunggu_verifikasi'
            ]);
            
            $isDP = $payment->method && $payment->method->type === 'installment';
            $messageText = ($isDP ? 'Bukti pembayaran DP (50%)' : 'Bukti pembayaran') . ' untuk pesanan ' . $order->order_number . ' via ' . ($payment->method->name ?? '-') . '.';
        }

        $chat = Chat::firstOrCreate([
            'user_id' => $user->id,
            'order_id' => null,
        ]);

        if ($request->filled('note')) {
            $messageText .= "\nCatatan: " . $request->note;
        }

        ChatMessage::create([
            'chat_id' => $chat->id,
            'sender_type' => 'customer',
            'sender_id' => $user->id,
            'message' => $messageText,
            'file_path' => $path,
        ]);

        return redirect()
            ->route('payments.show', $order->order_number)
            ->with('status', 'Bukti pembayaran berhasil diupload. Silakan tunggu verifikasi admin.');
    }

    public function gatewayCallback(Request $request, MidtransService $midtransService)
    {
        try {
            $orderId = $request->input('order_id');

            // Intercept Midtrans test notification immediately to avoid SDK 404 query errors
            if ($orderId && \Illuminate\Support\Str::startsWith($orderId, 'payment_notif_test_')) {
                return response()->json(['status' => 'ok', 'message' => 'Test notification received']);
            }

            $notification = $midtransService->handleNotification();

            if (!$notification) {
                return response()->json(['message' => 'Invalid notification payload'], 400);
            }

            $serverKey = config('services.midtrans.server_key');

            $hashed = hash(
                'sha512',
                $notification->order_id .
                $notification->status_code .
                $notification->gross_amount .
                $serverKey
            );

            if ($hashed !== $notification->signature_key) {
                return response()->json([
                    'message' => 'Invalid signature'
                ], 403);
            }

            $transaction = $notification->transaction_status ?? null;
            $type = $notification->payment_type ?? null;
            $orderId = $notification->order_id ?? ($notification->order_id ?? null);
            $fraud = $notification->fraud_status ?? null;

            /** @var Payment|null $payment */
            $payment = Payment::where('reference', $orderId)
                ->with('order', 'method', 'installments')
                ->first();

            if ($payment && $payment->status === 'lunas') {
                return response()->json([
                    'message' => 'Already processed'
                ]);
            }

            if (!$payment) {
                // Jika ini adalah test notifikasi dari dashboard Midtrans, kembalikan response 200 agar tidak error
                if ($orderId && \Illuminate\Support\Str::startsWith($orderId, 'payment_notif_test_')) {
                    return response()->json(['status' => 'ok', 'message' => 'Test notification received']);
                }
                return response()->json(['message' => 'Payment not found'], 404);
            }

            $updateData = [
                'raw_callback_log' => json_encode($request->all()),
                'transaction_id_gateway' => $notification->transaction_id ?? null,
            ];

            $isInstallment = $payment->method->type === 'installment';

            // Map Midtrans transaction statuses into local status values
            if ($transaction === 'capture') {
                if ($type === 'credit_card') {
                    if ($fraud === 'challenge') {
                        $updateData['status'] = 'challenge';
                    } else {
                        $updateData['status'] = $isInstallment ? 'sebagian_dibayar' : 'lunas';
                        if (!$isInstallment) {
                            $updateData['paid_at'] = now();
                        }
                    }
                }
            } elseif ($transaction === 'settlement') {
                $updateData['status'] = $isInstallment ? 'sebagian_dibayar' : 'lunas';
                $updateData['paid_at'] = now();

                $payment->order->update([
                    'payment_status' => $isInstallment
                        ? 'sebagian_dibayar'
                        : 'lunas',

                    'production_status' => 'Menunggu Konfirmasi',
                ]);

                // Auto create chat
                $chat = \App\Models\Chat::firstOrCreate([
                    'user_id' => $payment->order->user_id,
                    'order_id' => null,
                ]);
                
                \App\Models\ChatMessage::create([
                    'chat_id' => $chat->id,
                    'sender_type' => 'admin',
                    'sender_id' => 1, // Admin default id
                    'message' => 'Pembayaran otomatis Anda telah kami terima. Pesanan sedang diproses. Silakan balas pesan ini jika ada diskusi terkait pesanan/desain.',
                ]);

                // Auto send uploaded designs to chat
                foreach ($payment->order->items as $item) {
                    if ($item->design_file_path) {
                        \App\Models\ChatMessage::create([
                            'chat_id' => $chat->id,
                            'sender_type' => 'customer',
                            'sender_id' => $payment->order->user_id,
                            'message' => 'Berikut adalah lampiran file desain untuk produk: ' . ($item->product->name ?? 'Custom'),
                            'file_path' => $item->design_file_path,
                        ]);
                    }
                }
            } elseif ($transaction === 'pending') {
                $updateData['status'] = 'pending';
            } elseif (in_array($transaction, ['deny', 'expire', 'cancel'])) {
                $updateData['status'] = 'gagal';
                $payment->order->update([
                    'payment_status' => 'gagal',
                ]);
            } else {
                // Unknown status: record it for debugging
                \Log::warning('Midtrans webhook unknown status', ['transaction' => $transaction, 'payload' => $request->all()]);
            }

            $payment->update($updateData);

            return response()->json(['status' => 'ok']);
        } catch (\Exception $e) {
            \Log::error('Webhook Error: ' . $e->getMessage());
            return response()->json(['message' => 'Internal server error'], 500);
        }
    }

    public function simulateAuto(Payment $payment)
    {
        if (!in_array($payment->method->type ?? null, ['qris', 'ewallet', 'dana', 'ovo', 'shopeepay'])) {
            return back()->with('error', 'Simulasi hanya untuk QRIS, E-Wallet, DANA, OVO, ShopeePay, dan Cicilan (Via Gateway).');
        }

        $isInstallment = $payment->method->type === 'installment';

        $payment->update([
            'status' => $isInstallment ? 'sebagian_dibayar' : 'lunas',
            'paid_at' => now(),
        ]);

        $payment->order->update([
            'payment_status' => $isInstallment
                ? 'sebagian_dibayar'
                : 'lunas',

            'production_status' => 'Menunggu Konfirmasi',
        ]);

        // Auto create chat
        $chat = \App\Models\Chat::firstOrCreate([
            'user_id' => $payment->order->user_id,
            'order_id' => null,
        ]);
        
        \App\Models\ChatMessage::create([
            'chat_id' => $chat->id,
            'sender_type' => 'admin',
            'sender_id' => 1,
            'message' => 'Pembayaran Anda (Simulasi) telah kami terima. Pesanan sedang diproses. Silakan balas pesan ini jika ada diskusi terkait pesanan/desain.',
        ]);

        // Auto send uploaded designs to chat
        foreach ($payment->order->items as $item) {
            if ($item->design_file_path) {
                \App\Models\ChatMessage::create([
                    'chat_id' => $chat->id,
                    'sender_type' => 'customer',
                    'sender_id' => $payment->order->user_id,
                    'message' => 'Berikut adalah lampiran file desain untuk produk: ' . ($item->product->name ?? 'Custom'),
                    'file_path' => $item->design_file_path,
                ]);
            }
        }

        return back()->with('status', 'Pembayaran disimulasikan sebagai sukses oleh sistem.');
    }

    public function simulateSuccess(Payment $payment)
    {
        if (config('services.midtrans.is_production')) {
            abort(403, 'Aksi ini hanya diperbolehkan pada environment Sandbox.');
        }

        if (!in_array($payment->method->type ?? null, ['qris', 'ewallet', 'dana', 'ovo', 'shopeepay', 'installment'])) {
            return back()->with('error', 'Simulasi hanya untuk QRIS, E-Wallet, DANA, OVO, ShopeePay, dan Cicilan (Via Gateway).');
        }

        $isInstallment = $payment->method->type === 'installment';

        $payment->update([
            'status' => $isInstallment ? 'sebagian_dibayar' : 'lunas',
            'paid_at' => now(),
        ]);

        $payment->order->update([
            'payment_status' => $isInstallment
                ? 'sebagian_dibayar'
                : 'lunas',
            'production_status' => 'Menunggu Konfirmasi',
        ]);

        // Auto create chat
        $chat = \App\Models\Chat::firstOrCreate([
            'user_id' => $payment->order->user_id,
            'order_id' => null,
        ]);
        
        \App\Models\ChatMessage::create([
            'chat_id' => $chat->id,
            'sender_type' => 'admin',
            'sender_id' => 1,
            'message' => 'Simulasi Pembayaran Berhasil diterima. Pesanan sedang diproses. Silakan hubungi admin jika ada kendala.',
        ]);

        // Auto send uploaded designs to chat
        foreach ($payment->order->items as $item) {
            if ($item->design_file_path) {
                \App\Models\ChatMessage::create([
                    'chat_id' => $chat->id,
                    'sender_type' => 'customer',
                    'sender_id' => $payment->order->user_id,
                    'message' => 'Berikut adalah lampiran file desain untuk produk: ' . ($item->product->name ?? 'Custom'),
                    'file_path' => $item->design_file_path,
                ]);
            }
        }

        return back()->with('status', 'Pembayaran berhasil disimulasikan sebagai sukses (Lunas).');
    }

    public function simulateFailure(Payment $payment)
    {
        if (config('services.midtrans.is_production')) {
            abort(403, 'Aksi ini hanya diperbolehkan pada environment Sandbox.');
        }

        if (!in_array($payment->method->type ?? null, ['qris', 'ewallet', 'dana', 'ovo', 'shopeepay', 'installment'])) {
            return back()->with('error', 'Simulasi hanya untuk QRIS, E-Wallet, DANA, OVO, ShopeePay, dan Cicilan (Via Gateway).');
        }

        $payment->update([
            'status' => 'gagal',
        ]);

        $payment->order->update([
            'payment_status' => 'gagal',
        ]);

        return back()->with('status', 'Pembayaran berhasil disimulasikan sebagai gagal.');
    }

    public function retryGateway(Request $request, string $orderNumber, MidtransService $midtransService)
    {
        $order = Order::where('order_number', $orderNumber)->firstOrFail();

        /** @var Payment $payment */
        $payment = Payment::with('method')
            ->where('order_id', $order->id)
            ->orderByDesc('created_at')
            ->firstOrFail();

        $type = $payment->method->type ?? null;
        if (!in_array($type, ['qris', 'ewallet', 'dana', 'ovo', 'shopeepay'])) {
            return redirect()
                ->route('payments.show', $order->order_number)
                ->with('error', 'Metode ini tidak menggunakan Midtrans gateway. Silakan gunakan upload bukti/manual.');
        }

        $snapToken = $this->refreshSnapToken($order, $payment, $midtransService);
        if (!$snapToken) {
            return redirect()
                ->route('payments.show', $order->order_number)
                ->with('error', 'Gagal terhubung ke Midtrans. Coba lagi beberapa saat atau ganti metode pembayaran.');
        }

        return redirect()
            ->route('payments.show', $order->order_number)
            ->with('status', 'Koneksi pembayaran berhasil dipulihkan. Silakan klik Bayar Sekarang.');
    }

    private function refreshSnapToken(Order $order, Payment $payment, MidtransService $midtransService): ?string
    {
        $type = $payment->method->type ?? null;
        $enabled = [];

        switch ($type) {
            case 'qris':
                $enabled = ['gopay'];
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
            default:
                return null;
        }

        $newReference = 'PAY-' . strtoupper(Str::random(10));
        $transactionDetails = [
            'order_id' => $newReference,
            'gross_amount' => (int) ($payment->amount ?? $order->grand_total),
        ];

        $customerDetails = [
            'first_name' => $order->user->name ?? 'Customer',
            'email' => $order->user->email ?? null,
        ];

        $redirectUrl = route('payments.show', $order->order_number);
        $options = [
            'enabled_payments' => $enabled,
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

        // Filter out payment-method-specific callbacks if that payment method is not enabled
        if (!in_array('gopay', $enabled)) {
            unset($options['gopay']);
        }
        if (!in_array('shopeepay', $enabled)) {
            unset($options['shopeepay']);
        }
        if (!in_array('dana', $enabled)) {
            unset($options['dana']);
        }

        $snapToken = $midtransService->getSnapToken($transactionDetails, $customerDetails, [], $options);

        if ($snapToken) {
            $payment->update([
                'snap_token' => $snapToken,
                'reference' => $transactionDetails['order_id'],
            ]);
        }

        return $snapToken;
    }
}
