<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Promo;

class HomeController extends Controller
{
    public function index()
    {
        $featuredProducts = Product::where('is_active', true)->take(8)->get();
        $bestSellers = Product::withCount('orderItems')
            ->orderByDesc('order_items_count')
            ->take(8)
            ->get();

        $activePromos = Promo::where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('start_at')->orWhere('start_at', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('end_at')->orWhere('end_at', '>=', now());
            })
            ->get();

        return view('frontend.home', [
            'featuredProducts' => $featuredProducts,
            'bestSellers' => $bestSellers,
            'activePromos' => $activePromos,
        ]);
    }
}
