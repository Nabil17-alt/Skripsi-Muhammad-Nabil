@extends('layouts.frontend')

@section('title', 'Zimam Advertising - Cetak & Advertising Premium')

@section('content')
    <!-- Hero Section -->
    <div class="relative rounded-2xl overflow-hidden mb-12 shadow-lg group">
        <div class="absolute inset-0 bg-gradient-to-r from-blue-900/90 to-emerald-600/70 z-10"></div>
        <img src="https://images.unsplash.com/photo-1562564055-71e051d33c19?ixlib=rb-4.0.3&auto=format&fit=crop&w=1600&q=80" alt="Hero" class="w-full h-[400px] object-cover transition-transform duration-700 group-hover:scale-105">
        <div class="absolute inset-0 z-20 flex flex-col justify-center px-8 md:px-16 lg:px-24">
            <span class="inline-block px-3 py-1 bg-emerald-500/30 backdrop-blur-md border border-emerald-400/30 text-emerald-50 text-xs font-semibold rounded-full mb-4 w-fit tracking-wider uppercase">Layanan Cepat & Berkualitas</span>
            <h1 class="text-3xl md:text-5xl font-bold text-white mb-4 max-w-2xl leading-tight">Solusi Cetak & <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-300 to-white">Advertising</span> Untuk Bisnis Anda</h1>
            <p class="text-blue-50 mb-8 max-w-xl text-sm md:text-base leading-relaxed">Kami menghadirkan kualitas premium untuk setiap kebutuhan cetak Anda. Mulai dari kartu nama hingga billboard raksasa, kami siap membantu.</p>
            <div class="flex gap-4">
                <a href="{{ route('products.index') }}" class="btn-primary flex items-center gap-2">
                    Jelajahi Produk
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </a>
                <a href="{{ route('orders.track-form') }}" class="px-6 py-2 rounded-lg font-medium text-white border border-white/30 hover:bg-white/10 transition-colors backdrop-blur-sm">Lacak Pesanan</a>
            </div>
        </div>
    </div>

    <!-- Category Section -->
    <div class="mb-12">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-bold text-slate-800">Kategori Pilihan</h2>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @php
                $categories = [
                    ['name' => 'Banner & Spanduk', 'icon' => 'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z', 'color' => 'blue'],
                    ['name' => 'Kartu Nama', 'icon' => 'M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2', 'color' => 'emerald'],
                    ['name' => 'Brosur & Flyer', 'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z', 'color' => 'sky'],
                    ['name' => 'Merchandise', 'icon' => 'M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7', 'color' => 'cyan'],
                ];
            @endphp
            @foreach($categories as $cat)
            <a href="#" class="card-premium p-4 flex items-center gap-4 group">
                <div class="h-12 w-12 rounded-xl bg-{{$cat['color']}}-50 flex items-center justify-center text-{{$cat['color']}}-600 group-hover:bg-{{$cat['color']}}-600 group-hover:text-white transition-colors duration-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $cat['icon'] }}" />
                    </svg>
                </div>
                <span class="font-medium text-slate-700 group-hover:text-emerald-600 transition-colors">{{ $cat['name'] }}</span>
            </a>
            @endforeach
        </div>
    </div>

    <!-- Product Sections -->
    <div class="grid grid-cols-1 xl:grid-cols-4 gap-8">
        <div class="xl:col-span-3 space-y-12">
            
            <!-- Featured Products -->
            <div>
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center gap-2">
                        <svg class="w-6 h-6 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
                        Produk Unggulan
                    </h2>
                    <a href="{{ route('products.index') }}" class="text-sm font-medium text-emerald-600 hover:text-emerald-800 flex items-center gap-1 group">
                        Lihat Semua <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </a>
                </div>
                
                @if($featuredProducts->isEmpty())
                    <div class="bg-slate-50 border border-slate-200 rounded-xl p-8 text-center">
                        <p class="text-slate-500">Belum ada produk unggulan saat ini.</p>
                    </div>
                @else
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($featuredProducts as $product)
                            <div class="card-premium group flex flex-col h-full bg-white relative">
                                <div class="relative overflow-hidden aspect-video bg-slate-100 flex items-center justify-center">
                                    @php
                                        $primaryImage = $product->images->firstWhere('is_primary', true) ?? $product->images->first();
                                    @endphp
                                    @if($primaryImage && $primaryImage->image_path)
                                        <img src="{{ asset('storage/' . $primaryImage->image_path) }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                    @else
                                        <svg class="w-12 h-12 text-slate-300 group-hover:scale-110 transition-transform duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    @endif
                                    @if(isset($product->order_items_count) && $product->order_items_count > 10)
                                        <div class="absolute top-2 left-2 bg-emerald-500 text-white text-[10px] font-bold px-2 py-1 rounded">HOT</div>
                                    @endif
                                </div>
                                <div class="p-4 flex flex-col flex-1">
                                    <div class="text-xs text-slate-400 mb-1 uppercase tracking-wide font-semibold">{{ $product->category ?? 'Cetak' }}</div>
                                    <h3 class="font-bold text-slate-800 mb-2 leading-tight group-hover:text-emerald-600 transition-colors line-clamp-2">
                                        <a href="{{ route('products.show', $product->slug) }}" class="focus:outline-none">
                                            <span class="absolute inset-0" aria-hidden="true"></span>
                                            {{ $product->name }}
                                        </a>
                                    </h3>
                                    <div class="mt-auto pt-4 flex items-end justify-between">
                                        <div>
                                            <p class="text-[10px] text-slate-400 mb-0.5">Mulai dari</p>
                                            <p class="font-extrabold text-blue-600 text-lg leading-none">Rp {{ number_format($product->base_price, 0, ',', '.') }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Best Sellers -->
            <div>
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center gap-2">
                        <svg class="w-6 h-6 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                        Produk Terlaris
                    </h2>
                </div>
                
                @if($bestSellers->isEmpty())
                    <div class="bg-slate-50 border border-slate-200 rounded-xl p-8 text-center">
                        <p class="text-slate-500">Belum ada data penjualan.</p>
                    </div>
                @else
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($bestSellers as $product)
                            <div class="card-premium group flex flex-col h-full bg-white relative">
                                <div class="relative overflow-hidden aspect-video bg-slate-100 flex items-center justify-center">
                                    @php
                                        $primaryImage = $product->images->firstWhere('is_primary', true) ?? $product->images->first();
                                    @endphp
                                    @if($primaryImage && $primaryImage->image_path)
                                        <img src="{{ asset('storage/' . $primaryImage->image_path) }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                    @else
                                        <svg class="w-12 h-12 text-slate-300 group-hover:scale-110 transition-transform duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    @endif
                                </div>
                                <div class="p-4 flex flex-col flex-1">
                                    <div class="flex justify-between items-start mb-1">
                                        <div class="text-xs text-slate-400 uppercase tracking-wide font-semibold">{{ $product->category ?? 'Cetak' }}</div>
                                        <div class="text-[10px] font-semibold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded flex items-center gap-1">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                            Terjual {{ $product->order_items_count }}
                                        </div>
                                    </div>
                                    <h3 class="font-bold text-slate-800 mb-2 leading-tight group-hover:text-emerald-600 transition-colors line-clamp-2">
                                        <a href="{{ route('products.show', $product->slug) }}" class="focus:outline-none">
                                            <span class="absolute inset-0" aria-hidden="true"></span>
                                            {{ $product->name }}
                                        </a>
                                    </h3>
                                    <div class="mt-auto pt-4 flex items-end justify-between">
                                        <div>
                                            <p class="font-extrabold text-slate-900 text-lg leading-none">Rp {{ number_format($product->base_price, 0, ',', '.') }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <!-- Sidebar (Promos & Info) -->
        <div class="space-y-6">
            <div class="bg-gradient-to-br from-blue-600 to-emerald-600 rounded-2xl p-6 text-white shadow-lg relative overflow-hidden">
                <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-white opacity-10 rounded-full blur-xl"></div>
                <h3 class="font-bold text-lg mb-4 flex items-center gap-2 relative z-10">
                    <svg class="w-5 h-5 text-yellow-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"></path></svg>
                    Promo Spesial
                </h3>
                
                @forelse($activePromos as $promo)
                    <div class="bg-white/10 backdrop-blur-sm border border-white/20 rounded-xl p-4 mb-3 relative z-10">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-bold px-2 py-1 bg-yellow-400 text-yellow-900 rounded uppercase tracking-wide">{{ $promo->code }}</span>
                        </div>
                        <p class="text-sm text-blue-50 leading-snug">{{ $promo->description }}</p>
                    </div>
                @empty
                    <div class="bg-white/10 backdrop-blur-sm border border-white/20 rounded-xl p-4 text-center text-sm relative z-10">
                        Belum ada promo aktif. Pantau terus ya!
                    </div>
                @endforelse
            </div>

            <div class="card-premium p-6">
                <h3 class="font-bold text-slate-800 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Informasi Layanan
                </h3>
                <div class="space-y-4 text-sm text-slate-600">
                    <div class="flex gap-3">
                        <div class="mt-0.5 text-blue-500">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div>
                            <p class="font-semibold text-slate-800">Jam Operasional</p>
                            <p>Senin - Sabtu, 08.00 - 17.00 WIB</p>
                        </div>
                    </div>
                    <div class="flex gap-3">
                        <div class="mt-0.5 text-blue-500">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        </div>
                        <div>
                            <p class="font-semibold text-slate-800">Gratis Ongkir</p>
                            <p>Untuk area radius &lt; 1 KM dari toko.</p>
                        </div>
                    </div>
                    <div class="flex gap-3">
                        <div class="mt-0.5 text-blue-500">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                        </div>
                        <div>
                            <p class="font-semibold text-slate-800">Cicilan Tersedia</p>
                            <p>Transaksi minimal Rp {{ number_format(config('installments.min_amount'), 0, ',', '.') }}, bayar DP {{ config('installments.dp_percent') }}% sisanya dicicil sesuai tenor di profil Anda.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection