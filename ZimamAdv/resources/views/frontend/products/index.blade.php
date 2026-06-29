@extends('layouts.frontend')

@section('title', 'Produk - Zimam Advertising')

@section('content')
    <div class="mb-8">

        @if($products->isEmpty())
            <!-- Kosong State -->
            <div class="card-premium p-12 text-center">
                <svg class="w-16 h-16 text-slate-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z">
                    </path>
                </svg>
                <h3 class="text-lg font-semibold text-slate-900 mb-2">Belum ada produk</h3>
                <p class="text-slate-500">Produk sedang dipersiapkan. Silakan cek kembali nanti.</p>
            </div>
        @else
            <!-- Grid Produk -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($products as $product)
                    <div class="card-premium overflow-hidden hover:shadow-lg transition-all duration-300 flex flex-col">
                        <!-- Gambar Produk -->
                        <div
                            class="bg-gradient-to-br from-slate-100 to-slate-50 h-48 overflow-hidden flex items-center justify-center relative group">
                            @if($product->images && $product->images->count() > 0)
                                @php
                                    $primaryImage = $product->images->firstWhere('is_primary', true) ?? $product->images->first();
                                @endphp
                                @if($primaryImage && $primaryImage->image_path)
                                    <img src="{{ asset('storage/' . $primaryImage->image_path) }}" alt="{{ $product->name }}"
                                        class="w-full h-full object-contain p-3 group-hover:scale-105 transition-transform duration-300"
                                        loading="lazy">

                                    @if($product->images->count() > 1)
                                        <div
                                            class="absolute top-2 right-2 bg-emerald-600 text-white text-xs font-bold px-2 py-1 rounded-full shadow-lg z-10">
                                            📸 {{ $product->images->count() }}
                                        </div>
                                    @endif
                                @else
                                    <div class="flex flex-col items-center justify-center text-center p-4">
                                        <svg class="w-12 h-12 text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                        <p class="text-xs text-slate-400">Path gambar tidak ditemukan</p>
                                    </div>
                                @endif
                            @else
                                <div class="flex flex-col items-center justify-center text-center p-4">
                                    <svg class="w-12 h-12 text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                    <p class="text-xs text-slate-400">Belum ada gambar</p>
                                </div>
                            @endif
                        </div>

                        <!-- Info Produk -->
                        <div class="p-4 flex flex-col flex-1 space-y-3">
                            <!-- Kategori -->
                            @if($product->category)
                                <span
                                    class="inline-block bg-sky-50 text-sky-700 px-2 py-1 rounded text-[10px] font-semibold uppercase tracking-wide w-fit">
                                    {{ $product->category }}
                                </span>
                            @endif

                            <!-- Nama & Deskripsi -->
                            <div class="flex-1">
                                <h3 class="font-semibold text-slate-900 mb-1 line-clamp-2">{{ $product->name }}</h3>
                                <p class="text-xs text-slate-600 line-clamp-2">{{ $product->description }}</p>
                            </div>

                            <!-- Harga & Info -->
                            <div class="space-y-2 border-t border-slate-100 pt-3">
                                <div class="flex items-baseline justify-between">
                                    <p class="text-lg font-bold text-emerald-600">
                                        Rp {{ number_format($product->base_price, 0, ',', '.') }}
                                    </p>
                                    <span class="text-[10px] text-slate-500 font-medium">per unit</span>
                                </div>

                                <!-- Waktu Pengerjaan -->
                                <div class="flex items-center gap-1 text-xs text-slate-600">
                                    <svg class="w-3.5 h-3.5 text-slate-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v3.586L7.707 9.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 10.586V7z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    <span>{{ $product->lead_time_days }} hari pengerjaan</span>
                                </div>
                            </div>

                            <!-- Button Detail -->
                            <a href="{{ route('products.show', $product->slug) }}"
                                class="btn-primary w-full text-center py-2 font-medium rounded-lg transition-all hover:shadow-md">
                                Lihat Detail
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($products->hasPages())
                <div class="mt-8">
                    {{ $products->links() }}
                </div>
            @endif
        @endif
    </div>
@endsection