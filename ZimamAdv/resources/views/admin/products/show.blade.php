@extends('layouts.admin')

@section('page_title', 'Detail Produk')

@section('content')
    <div class="w-full space-y-6">
        <!-- Header dengan Gambar -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Galeri Gambar -->
            <div class="md:col-span-1">
                <div class="card overflow-hidden">
                    <div class="aspect-square bg-slate-100 relative">
                        @if($product->images->count() > 0)
                            @php
                                $primaryImage = $product->images->firstWhere('is_primary', true) ?? $product->images->first();
                            @endphp
                            <img id="main-image" src="{{ asset('storage/' . $primaryImage->image_path) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <svg class="w-12 h-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                        @endif
                    </div>

                    @if($product->images->count() > 1)
                        <div class="p-3 border-t border-slate-200 flex gap-2 overflow-x-auto">
                            @foreach($product->images as $image)
                                <button type="button" onclick="switchImage('{{ asset('storage/' . $image->image_path) }}')" 
                                    class="flex-shrink-0 h-12 w-12 rounded border-2 border-slate-200 hover:border-emerald-500 overflow-hidden transition-colors">
                                    <img src="{{ asset('storage/' . $image->image_path) }}" alt="Thumbnail" class="w-full h-full object-cover">
                                </button>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <!-- Informasi Produk -->
            <div class="md:col-span-2 space-y-4">
                <!-- Nama & Status -->
                <div class="card card-body">
                    <div class="flex items-start justify-between mb-3">
                        <div>
                            <h2 class="text-2xl font-bold text-slate-900">{{ $product->name }}</h2>
                            <p class="text-sm text-slate-500 mt-1">{{ $product->slug }}</p>
                        </div>
                        <span class="badge-status {{ $product->is_active ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">
                            {{ $product->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </div>
                </div>

                <!-- Harga & Kategori -->
                <div class="grid grid-cols-2 gap-3">
                    <div class="card card-body">
                        <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1">Harga Dasar</p>
                        <p class="text-2xl font-bold text-emerald-600">Rp {{ number_format($product->base_price, 0, ',', '.') }}</p>
                    </div>
                    <div class="card card-body">
                        <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1">Kategori</p>
                        <p class="text-lg font-semibold text-slate-800">{{ $product->category ?? '-' }}</p>
                    </div>
                </div>

                <!-- Konfigurasi -->
                <div class="card card-body">
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-3">Konfigurasi</p>
                    <div class="space-y-2">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-slate-600">Estimasi Pengerjaan:</span>
                            <span class="font-semibold text-slate-900">{{ $product->lead_time_days }} hari</span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-slate-600">Desain Custom:</span>
                            <span class="font-semibold">
                                @if($product->allow_custom_design)
                                    <span class="text-emerald-600">✓ Diizinkan</span>
                                @else
                                    <span class="text-slate-500">✗ Tidak</span>
                                @endif
                            </span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-slate-600">Jasa Desain:</span>
                            <span class="font-semibold">
                                @if($product->allow_design_service)
                                    <span class="text-emerald-600">✓ Tersedia</span>
                                @else
                                    <span class="text-slate-500">✗ Tidak</span>
                                @endif
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Deskripsi -->
        @if($product->description)
            <div class="card card-body">
                <h3 class="text-sm font-semibold text-slate-700 mb-3">Deskripsi Produk</h3>
                <p class="text-sm text-slate-700 whitespace-pre-wrap">{{ $product->description }}</p>
            </div>
        @endif

        <!-- Varian Produk -->
        @if($product->variants->count() > 0)
            <div class="card">
                <div class="card-header">
                    <span>Varian Produk ({{ $product->variants->count() }})</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-slate-50 text-slate-600 border-b border-slate-200">
                            <tr>
                                <th class="px-4 py-2 text-left font-semibold">Nama</th>
                                <th class="px-4 py-2 text-right font-semibold">Harga</th>
                                <th class="px-4 py-2 text-left font-semibold">Stok</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($product->variants as $variant)
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-4 py-3">
                                        <p class="font-medium text-slate-900">{{ $variant->name }}</p>
                                        @if($variant->description)
                                            <p class="text-xs text-slate-500 mt-0.5">{{ $variant->description }}</p>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-right font-semibold text-emerald-600">Rp {{ number_format($variant->price, 0, ',', '.') }}</td>
                                    <td class="px-4 py-3">
                                        <span class="text-slate-700">{{ $variant->stock ?? '-' }} unit</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        <!-- Informasi Tambahan -->
        <div class="grid grid-cols-2 gap-4">
            <div class="card card-body text-sm">
                <p class="text-slate-500 mb-1">Dibuat</p>
                <p class="font-medium text-slate-900">{{ $product->created_at->format('d M Y H:i') }}</p>
            </div>
            <div class="card card-body text-sm">
                <p class="text-slate-500 mb-1">Diperbarui</p>
                <p class="font-medium text-slate-900">{{ $product->updated_at->format('d M Y H:i') }}</p>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex justify-end gap-3">
            <a href="{{ route('admin.products.index') }}" class="btn-secondary text-xs">Kembali</a>
            <a href="{{ route('admin.products.edit', $product) }}" class="btn-primary text-xs">Edit Produk</a>
        </div>
    </div>

    <script>
        function switchImage(imageSrc) {
            document.getElementById('main-image').src = imageSrc;
        }
    </script>
@endsection
