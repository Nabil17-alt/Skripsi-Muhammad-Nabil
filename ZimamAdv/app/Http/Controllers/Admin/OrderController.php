<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('user')->orderByDesc('created_at')->paginate(20);

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load('items.product', 'payments', 'shippingAddress');

        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'production_status' => 'required|string',
        ]);

        $oldStatus = $order->production_status;
        $newStatus = $request->production_status;

        $order->update([
            'production_status' => $newStatus,
        ]);

        // Kirim pesan otomatis jika status berubah ke "Siap 50% (Ambil di Toko)"
        if (strcasecmp($newStatus, 'Siap 50% (Ambil di Toko)') == 0 && strcasecmp($oldStatus, 'Siap 50% (Ambil di Toko)') != 0) {
            $chat = \App\Models\Chat::firstOrCreate([
                'user_id' => $order->user_id,
                'order_id' => null,
            ]);

            \App\Models\ChatMessage::create([
                'chat_id' => $chat->id,
                'sender_type' => 'admin',
                'sender_id' => auth()->id() ?? 1, // Dynamically use admin ID, fallback to 1
                'message' => "Pesanan #{$order->order_number} Siap 50% & Dapat Diambil di Toko!",
                'is_read' => false,
            ]);
        }

        return redirect()->route('admin.orders.show', $order)->with('status', 'Status produksi berhasil diperbarui.');
    }
}
