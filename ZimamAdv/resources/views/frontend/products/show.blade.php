@extends('layouts.frontend')

@section('title', $product->name . ' - Zimam Advertising')

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12">
        <!-- Galeri Produk -->
        <div class="space-y-4">
            @if($product->images->count() > 0)
                @php
                    $primaryImage = $product->images->firstWhere('is_primary', true) ?? $product->images->first();
                @endphp
                <!-- Gambar Utama -->
                <div class="card overflow-hidden bg-slate-50 h-96 flex items-center justify-center">
                    <img id="main-image" src="{{ asset('storage/' . $primaryImage->image_path) }}" alt="{{ $product->name }}"
                        class="w-full h-full object-contain p-4">
                </div>

                <!-- Thumbnail Galeri -->
                @if($product->images->count() > 1)
                    <div class="flex gap-3 overflow-x-auto pb-2">
                        @foreach($product->images as $image)
                            <button type="button" onclick="switchImage('{{ asset('storage/' . $image->image_path) }}')"
                                class="flex-shrink-0 h-20 w-20 rounded-lg border-2 border-slate-200 hover:border-emerald-500 overflow-hidden bg-slate-50 transition-all cursor-pointer"
                                :class="{ 'ring-2 ring-emerald-500': mainImage === '{{ asset('storage/' . $image->image_path) }}' }">
                                <img src="{{ asset('storage/' . $image->image_path) }}" alt="Thumbnail"
                                    class="w-full h-full object-contain">
                            </button>
                        @endforeach
                    </div>
                @endif
            @else
                <!-- Placeholder jika tidak ada gambar -->
                <div
                    class="card overflow-hidden bg-gradient-to-br from-slate-100 to-slate-200 h-96 flex flex-col items-center justify-center rounded-2xl">
                    <svg class="w-20 h-20 text-slate-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                        </path>
                    </svg>
                    <p class="text-slate-500 text-center">
                        <span class="font-semibold">Belum ada gambar</span><br>
                        <span class="text-sm">Hubungi admin untuk info lebih lanjut</span>
                    </p>
                </div>
            @endif
        </div>

        <!-- Detail & Form Pesanan -->
        <div class="space-y-6">
            <!-- Informasi Produk -->
            <div class="card-premium p-6 space-y-4">
                <div>
                    <div class="flex items-start justify-between mb-2">
                        <div>
                            <h1 class="text-3xl font-bold text-slate-900">{{ $product->name }}</h1>
                            <p class="text-sm text-slate-500 mt-1">
                                <span
                                    class="inline-block bg-emerald-50 text-emerald-700 px-3 py-1 rounded-full text-xs font-semibold">
                                    {{ $product->category ?? 'Umum' }}
                                </span>
                            </p>
                        </div>
                    </div>
                    @if($product->description)
                        <p class="text-slate-700 leading-relaxed text-sm mt-3">{{ $product->description }}</p>
                    @endif
                </div>

                <!-- Harga -->
                <div class="border-t border-slate-200 pt-4">
                    <div class="flex items-baseline gap-2">
                        <span class="text-3xl font-bold text-emerald-600">Rp
                            {{ number_format($product->base_price, 0, ',', '.') }}</span>
                        <span class="text-sm text-slate-500">per unit</span>
                    </div>
                </div>

                <!-- Info Pengerjaan -->
                <div class="grid grid-cols-2 gap-3">
                    <div class="bg-sky-50 rounded-lg p-3 border border-sky-200">
                        <p class="text-xs text-sky-700 font-semibold uppercase tracking-wide mb-1">Waktu Pengerjaan</p>
                        <p class="text-lg font-bold text-sky-900">{{ $product->lead_time_days }} hari</p>
                    </div>
                    <div class="bg-emerald-50 rounded-lg p-3 border border-emerald-200">
                        <p class="text-xs text-emerald-700 font-semibold uppercase tracking-wide mb-1">Foto Produk</p>
                        <p class="text-lg font-bold text-emerald-900">{{ $product->images->count() }}</p>
                    </div>
                </div>

                <!-- Fitur Layanan -->
                <div class="space-y-2 text-sm">
                    @if($product->allow_custom_design)
                        <div class="flex items-center gap-2 text-slate-600">
                            <svg class="w-4 h-4 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd" />
                                </path>
                            </svg>
                            <span>Desain Custom Tersedia</span>
                        </div>
                    @endif
                    @if($product->allow_design_service)
                        <div class="flex items-center gap-2 text-slate-600">
                            <svg class="w-4 h-4 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd" />
                                </path>
                            </svg>
                            <span>Jasa Desain Profesional</span>
                        </div>
                    @endif
                </div>
            </div>



            <!-- Form Pesanan -->
            <div class="card-premium p-6">
                <h3 class="text-lg font-semibold text-slate-900 mb-4">Pesan Sekarang</h3>
                <form action="{{ route('cart.store') }}" method="POST" class="space-y-4" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">

                    <!-- Opsi Desain -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Opsi Desain</label>
                        <div class="space-y-2">
                            @if($product->allow_custom_design)
                                <label
                                    class="flex flex-col gap-2 p-3 border border-slate-200 rounded-lg hover:bg-slate-50 cursor-pointer">
                                    <div class="flex items-start gap-3">
                                        <input type="radio" name="design_option" value="custom" class="mt-1" checked onchange="toggleDesignFile(true)">
                                        <div>
                                            <p class="text-sm font-medium text-slate-900">Desain Sendiri</p>
                                            <p class="text-xs text-slate-500">Upload file desain Anda di bawah</p>
                                        </div>
                                    </div>
                                    <div id="custom-design-upload" class="mt-2 pl-7">
                                        <input type="file" name="design_file" class="block w-full text-sm text-slate-500
                                            file:mr-4 file:py-2 file:px-4
                                            file:rounded-full file:border-0
                                            file:text-sm file:font-semibold
                                            file:bg-emerald-50 file:text-emerald-700
                                            hover:file:bg-emerald-100
                                        " accept=".pdf,.jpg,.jpeg,.png,.zip,.rar,.ai,.cdr">
                                    </div>
                                </label>
                            @endif
                            @if($product->allow_design_service)
                                <label
                                    class="flex items-start gap-3 p-3 border border-slate-200 rounded-lg hover:bg-slate-50 cursor-pointer">
                                    <input type="radio" name="design_option" value="service" class="mt-1" onchange="toggleDesignFile(false)">
                                    <div>
                                        <p class="text-sm font-medium text-slate-900">Jasa Desain Zimam (+Rp {{ number_format($product->design_service_fee, 0, ',', '.') }})</p>
                                        <p class="text-xs text-slate-500">Dibuatkan oleh desainer profesional kami</p>
                                    </div>
                                </label>
                            @endif
                        </div>
                    </div>

                    <!-- Jumlah -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Jumlah Pesanan</label>
                        <div class="flex items-center gap-3">
                            <button type="button" onclick="decreaseQty()"
                                class="p-2 rounded-lg border border-slate-300 hover:bg-slate-100">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"
                                        clip-rule="evenodd" />
                                    </path>
                                </svg>
                            </button>
                            <input type="number" id="qty-input" name="quantity" value="1" min="1"
                                class="form-input w-20 text-center">
                            <button type="button" onclick="increaseQty()"
                                class="p-2 rounded-lg border border-slate-300 hover:bg-slate-100">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                                        clip-rule="evenodd" />
                                    </path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Catatan -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Catatan Khusus (Opsional)</label>
                        <textarea name="notes" rows="3" class="form-input text-sm"
                            placeholder="Contoh: Ukuran 3x1 m, warna dominan biru, bahan glossy, dll..."></textarea>
                    </div>

                    <!-- Button Pesan -->
                    <button type="submit" class="btn-primary w-full py-3 font-semibold text-base rounded-xl">
                        🛒 Tambah ke Keranjang
                    </button>

                    <!-- Info Tambahan -->
                    <div class="p-3 bg-emerald-50 border border-emerald-200 rounded-lg">
                        <p class="text-xs text-emerald-700">
                            <span class="font-semibold">💡 Tips:</span> Hubungi tim customer service kami melalui chat jika
                            ada pertanyaan atau kebutuhan khusus.
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function switchImage(imageSrc) {
            document.getElementById('main-image').src = imageSrc;
        }

        function increaseQty() {
            const input = document.getElementById('qty-input');
            input.value = parseInt(input.value) + 1;
        }

        function decreaseQty() {
            const input = document.getElementById('qty-input');
            if (parseInt(input.value) > 1) {
                input.value = parseInt(input.value) - 1;
            }
        }

        function toggleDesignFile(show) {
            const uploadDiv = document.getElementById('custom-design-upload');
            if (uploadDiv) {
                uploadDiv.style.display = show ? 'block' : 'none';
            }
        }

        // Initialize display state
        document.addEventListener('DOMContentLoaded', function() {
            const checkedOption = document.querySelector('input[name="design_option"]:checked');
            if (checkedOption) {
                toggleDesignFile(checkedOption.value === 'custom');
            }
        });
    </script>
@endsection