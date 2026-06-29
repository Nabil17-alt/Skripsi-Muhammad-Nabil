@extends('layouts.admin')

@section('page_title', 'Produk')

@section('content')
    <div class="mb-6 flex justify-between items-center">
        <div class="text-sm text-slate-500">Kelola semua produk yang tersedia di toko online.</div>
        <a href="{{ route('admin.products.create') }}" class="btn-primary text-xs">Tambah Produk</a>
    </div>

    @if($products->count() > 0)
        <div class="table-wrapper">
            <table class="table-default">
                <thead>
                    <tr>
                        <th style="width: 60px;">Gambar</th>
                        <th>Nama Produk</th>
                        <th>Kategori</th>
                        <th class="text-right">Harga Dasar</th>
                        <th class="text-center">Hari</th>
                        <th class="text-center">Status</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $product)
                        <tr>
                            <td class="text-center">
                                @php
                                    $primaryImage = $product->images->firstWhere('is_primary', true) ?? $product->images->first();
                                @endphp
                                @if($primaryImage)
                                    <div class="inline-flex h-10 w-10 rounded-md border border-slate-200 overflow-hidden bg-slate-50">
                                        <img src="{{ asset('storage/' . $primaryImage->image_path) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                                    </div>
                                @else
                                    <div class="inline-flex h-10 w-10 rounded-md border border-slate-200 bg-slate-100 items-center justify-center">
                                        <svg class="w-5 h-5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <p class="font-medium text-slate-900">{{ $product->name }}</p>
                                <p class="text-xs text-slate-500 mt-0.5">{{ $product->images->count() }} foto</p>
                            </td>
                            <td>{{ $product->category ?? '-' }}</td>
                            <td class="text-right font-semibold text-sky-600">Rp {{ number_format($product->base_price, 0, ',', '.') }}</td>
                            <td class="text-center text-sm text-slate-600">{{ $product->lead_time_days }}d</td>
                            <td class="text-center">
                                <span class="badge-status {{ $product->is_active ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">
                                    {{ $product->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td class="text-right flex items-center justify-end gap-2">
                                <a href="{{ route('admin.products.show', $product) }}" class="btn-secondary text-xs">Detail</a>
                                <a href="{{ route('admin.products.edit', $product) }}" class="btn-secondary text-xs">Edit</a>
                                <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-rose-600 text-xs hover:text-rose-700" onclick="return confirm('Hapus produk dan semua fotonya?')">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($products->hasPages())
            <div class="mt-6 px-4 py-3">
                {{ $products->links() }}
            </div>
        @endif
    @else
        <div class="card card-body text-center py-12">
            <svg class="mx-auto h-12 w-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
            <p class="text-slate-600 mt-4">Belum ada produk</p>
            <a href="{{ route('admin.products.create') }}" class="btn-primary text-xs mt-4 inline-block">Buat Produk Pertama</a>
        </div>
    @endif
@endsection
