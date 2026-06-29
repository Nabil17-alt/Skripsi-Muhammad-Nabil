<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $orders = Order::where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('frontend.orders.index', compact('orders'));
    }

    public function show(string $orderNumber)
    {
        $user = auth()->user();

        $order = Order::where('order_number', $orderNumber)
            ->where('user_id', $user->id)
            ->with('items.product')
            ->firstOrFail();

        return view('frontend.orders.show', compact('order'));
    }

    public function trackForm()
    {
        return view('frontend.orders.track');
    }

    public function track(Request $request)
    {
        $data = $request->validate([
            'order_number' => 'required|string',
        ]);

        $order = Order::where('order_number', $data['order_number'])->first();

        if (!$order) {
            return back()->withErrors(['order_number' => 'Nomor pesanan tidak ditemukan.']);
        }

        return view('frontend.orders.show', compact('order'));
    }
}
