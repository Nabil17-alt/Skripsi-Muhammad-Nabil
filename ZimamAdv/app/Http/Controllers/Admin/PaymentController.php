<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::with('order')->orderByDesc('created_at')->paginate(20);

        return view('admin.payments.index', compact('payments'));
    }

    public function show(Payment $payment)
    {
        $payment->load('order', 'installments', 'method');

        return view('admin.payments.show', compact('payment'));
    }

    public function markInstallmentPaid(Payment $payment, int $sequence)
    {
        $installment = $payment->installments()->where('sequence', $sequence)->firstOrFail();

        $installment->update([
            'status' => 'paid',
            'paid_at' => now(),
        ]);

        // Auto create chat
        $chat = \App\Models\Chat::firstOrCreate([
            'user_id' => $payment->order->user_id,
            'order_id' => null,
        ]);

        \App\Models\ChatMessage::create([
            'chat_id' => $chat->id,
            'sender_type' => 'admin',
            'sender_id' => auth()->id() ?? 1,
            'message' => 'Pembayaran Cicilan ke-' . $installment->sequence . ' untuk pesanan ' . $payment->order->order_number . ' telah kami verifikasi. Terima kasih.',
        ]);

        // Jika semua cicilan sudah dibayar, tandai payment dan order sebagai lunas
        if ($payment->installments()->where('status', '!=', 'paid')->count() === 0) {
            $payment->update([
                'status' => 'lunas',
                'paid_at' => now(),
            ]);
            $payment->order->update([
                'payment_status' => 'lunas',
            ]);
            
            \App\Models\ChatMessage::create([
                'chat_id' => $chat->id,
                'sender_type' => 'admin',
                'sender_id' => auth()->id() ?? 1,
                'message' => 'Selamat! Seluruh angsuran untuk pesanan ' . $payment->order->order_number . ' telah LUNAS. Terima kasih atas kepercayaan Anda.',
            ]);
        }

        return back();
    }

    public function verify(Request $request, Payment $payment)
    {
        $isInstallment = $payment->method && $payment->method->type === 'installment';

        if ($isInstallment) {
            $payment->update(['status' => 'sebagian_dibayar']);
            $payment->order->update(['payment_status' => 'sebagian_dibayar']);
            $message = 'Pembayaran DP (50%) Anda telah berhasil kami verifikasi. Pesanan Anda segera diproses. Sisa angsuran dapat dibayarkan sesuai jadwal.';
        } else {
            $payment->update(['status' => 'lunas', 'paid_at' => now()]);
            $payment->order->update(['payment_status' => 'lunas']);
            $message = 'Pembayaran Anda telah berhasil kami verifikasi. Pesanan sedang diproses. Silakan balas pesan ini jika ada diskusi terkait pesanan/desain.';
        }

        // Auto create chat
        $chat = \App\Models\Chat::firstOrCreate([
            'user_id' => $payment->order->user_id,
            'order_id' => null,
        ]);
        
        \App\Models\ChatMessage::create([
            'chat_id' => $chat->id,
            'sender_type' => 'admin',
            'sender_id' => auth()->id() ?? 1,
            'message' => $message,
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

        return redirect()->route('admin.payments.show', $payment);
    }

    public function installments()
    {
        $installments = \App\Models\Installment::with('payment.order.user')
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('admin.installments.index', compact('installments'));
    }
}
