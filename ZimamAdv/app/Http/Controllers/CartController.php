<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);

        return view('frontend.cart.index', compact('cart'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:tb_products,id',
            'quantity' => 'required|integer|min:1',
            'design_option' => 'nullable|in:custom,service',
            'notes' => 'nullable|string',
            'design_file' => 'nullable|file|max:10240', // max 10MB
        ]);

        $product = Product::findOrFail($request->product_id);
        $cart = session()->get('cart', []);

        $cartItemKey = (string) $product->id;
        
        $filePath = null;
        if ($request->hasFile('design_file') && $request->input('design_option') === 'custom') {
            // Simpan ke temp folder
            $file = $request->file('design_file');
            $filename = uniqid('temp_design_') . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('temp', $filename, 'public');
        }

        if (isset($cart[$cartItemKey]) && !$filePath) {
            $cart[$cartItemKey]['quantity'] += (int) $request->quantity;
            // update notes if provided
            if ($request->notes) {
                $cart[$cartItemKey]['notes'] = $request->notes;
            }
        } else {
            // If there's a new file, we could potentially create a new unique key, but for simplicity we overwrite/create new
            if ($filePath) {
                $cartItemKey = $product->id . '_' . uniqid();
            }
            $cart[$cartItemKey] = [
                'product_id' => $product->id,
                'name' => $product->name,
                'price' => $product->base_price,
                'quantity' => (int) $request->quantity,
                'design_option' => $request->input('design_option', $product->allow_custom_design ? 'custom' : 'service'),
                'design_service_fee' => $product->design_service_fee,
                'notes' => $request->input('notes'),
                'design_file' => $filePath,
            ];
        }

        session()->put('cart', $cart);

        return redirect()->route('cart.index');
    }

    public function update(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
        ]);

        $cart = session()->get('cart', []);

        foreach ($request->items as $key => $quantity) {
            if (isset($cart[$key])) {
                $qty = max(1, (int) $quantity);
                $cart[$key]['quantity'] = $qty;
            }
        }

        session()->put('cart', $cart);

        return redirect()->route('cart.index');
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'key' => 'required',
        ]);

        $cart = session()->get('cart', []);
        unset($cart[$request->key]);
        session()->put('cart', $cart);

        return redirect()->route('cart.index');
    }
}
