<?php

namespace App\Http\Controllers;

use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('images')->where('is_active', true)->paginate(12);

        return view('frontend.products.index', compact('products'));
    }

    public function show(string $slug)
    {
        $product = Product::where('slug', $slug)->where('is_active', true)->with('images', 'variants')->firstOrFail();

        return view('frontend.products.show', compact('product'));
    }
}
