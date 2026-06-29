@extends('layouts.admin')

@section('page_title', 'Edit Produk')

@section('content')
    <div class="w-full">
        <div class="card">
            <div class="card-header">
                <span>Edit Data Produk</span>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Informasi Dasar Produk -->
                    <div class="border-b border-slate-200 pb-6">
                        <h3 class="text-sm font-semibold text-slate-700 mb-4 flex items-center gap-2">
                            <svg class="w-4 h-4 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 5v8a2 2 0 01-2 2h-5l-5 4v-4H4a2 2 0 01-2-2V5a2 2 0 012-2h12a2 2 0 012 2zm-11-1h2v2H7V4zm2 4H7v2h2V8zm2-4h2v2h-2V4zm2 4h-2v2h2V8z" clip-rule="evenodd"></path></svg>
                            Informasi Dasar
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="name" class="form-label">Nama Produk</label>
                                <input type="text" id="name" name="name" class="form-input @error('name') border-rose-500 @enderror" 
                                    value="{{ old('name', $product->name) }}" placeholder="Nama produk">
                                @error('name')
                                    <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="slug" class="form-label">Slug</label>
                                <input type="text" id="slug" name="slug" class="form-input @error('slug') border-rose-500 @enderror" 
                                    value="{{ old('slug', $product->slug) }}" placeholder="produk-nama">
                                @error('slug')
                                    <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="category" class="form-label">Kategori</label>
                                <input type="text" id="category" name="category" class="form-input @error('category') border-rose-500 @enderror" 
                                    value="{{ old('category', $product->category) }}" placeholder="Kategori produk">
                                @error('category')
                                    <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="base_price" class="form-label">Harga Dasar</label>
                                <input type="number" id="base_price" name="base_price" class="form-input @error('base_price') border-rose-500 @enderror" 
                                    value="{{ old('base_price', $product->base_price) }}" min="0" placeholder="0">
                                @error('base_price')
                                    <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-4">
                            <label for="description" class="form-label">Deskripsi</label>
                            <textarea id="description" name="description" class="form-input @error('description') border-rose-500 @enderror" 
                                rows="3" placeholder="Deskripsi lengkap produk">{{ old('description', $product->description) }}</textarea>
                            @error('description')
                                <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Konfigurasi Produk -->
                    <div class="border-b border-slate-200 pb-6">
                        <h3 class="text-sm font-semibold text-slate-700 mb-4 flex items-center gap-2">
                            <svg class="w-4 h-4 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"></path></svg>
                            Konfigurasi
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="lead_time_days" class="form-label">Estimasi Hari Pengerjaan</label>
                                <input type="number" id="lead_time_days" name="lead_time_days" class="form-input @error('lead_time_days') border-rose-500 @enderror" 
                                    value="{{ old('lead_time_days', $product->lead_time_days) }}" min="1" placeholder="1">
                                @error('lead_time_days')
                                    <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="design_service_fee" class="form-label">Biaya Jasa Desain (Rp)</label>
                                <input type="number" id="design_service_fee" name="design_service_fee" class="form-input @error('design_service_fee') border-rose-500 @enderror" 
                                    value="{{ old('design_service_fee', (int)$product->design_service_fee) }}" min="0" placeholder="0">
                                @error('design_service_fee')
                                    <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-4 flex gap-6">
                            <label for="allow_custom_design" class="flex items-center gap-2.5 cursor-pointer group">
                                <input type="checkbox" id="allow_custom_design" name="allow_custom_design" value="1" class="w-4 h-4 rounded border-slate-300 text-emerald-600" {{ old('allow_custom_design', $product->allow_custom_design) ? 'checked' : '' }}>
                                <span class="text-sm text-slate-700 group-hover:text-slate-900">Izinkan Desain Custom</span>
                            </label>

                            <label for="allow_design_service" class="flex items-center gap-2.5 cursor-pointer group">
                                <input type="checkbox" id="allow_design_service" name="allow_design_service" value="1" class="w-4 h-4 rounded border-slate-300 text-emerald-600" {{ old('allow_design_service', $product->allow_design_service) ? 'checked' : '' }}>
                                <span class="text-sm text-slate-700 group-hover:text-slate-900">Jasa Desain</span>
                            </label>
                        </div>
                    </div>

                    <!-- Galeri Produk -->
                    <div class="border-b border-slate-200 pb-6">
                        <h3 class="text-sm font-semibold text-slate-700 mb-4 flex items-center gap-2">
                            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            Galeri Produk
                        </h3>

                        <!-- Gambar yang Sudah Ada -->
                        @if($product->images->count() > 0)
                            <div class="mb-6 p-4 bg-slate-50 rounded-lg border border-slate-200">
                                <h4 class="text-xs font-semibold text-slate-600 uppercase tracking-wide mb-3">Gambar Saat Ini</h4>
                                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
                                    @foreach($product->images as $image)
                                        <div class="relative group {{ $image->is_primary ? 'ring-2 ring-emerald-500' : '' }}" data-image-id="{{ $image->id }}">
                                            <div class="aspect-square rounded-lg overflow-hidden border border-slate-200 bg-slate-100">
                                                <img src="{{ asset('storage/' . $image->image_path) }}" alt="Produk" class="w-full h-full object-cover">
                                                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2">
                                                    @if(!$image->is_primary)
                                                        <button type="button" class="bg-emerald-600 text-white rounded-full p-2 hover:bg-emerald-700 transition-colors" title="Set sebagai gambar utama" onclick="setAsPrimary(event, {{ $image->id }})">
                                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                                        </button>
                                                    @endif
                                                    <button type="submit" form="delete-image-form-{{ $image->id }}" class="bg-rose-600 text-white rounded-full p-2 hover:bg-rose-700 transition-colors" title="Hapus gambar" onclick="return confirm('Hapus gambar ini?')">
                                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                                                    </button>
                                                </div>
                                                @if($image->is_primary)
                                                    <div class="absolute top-1 right-1 bg-emerald-600 text-white text-[10px] font-bold px-2 py-1 rounded flex items-center gap-1">
                                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                                        UTAMA
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <input type="hidden" id="primary_image_id" name="primary_image_id" value="{{ $product->images->firstWhere('is_primary', true)?->id }}">
                            </div>
                        @else
                            <div class="mb-6 p-4 bg-amber-50 border border-amber-200 rounded-lg text-sm text-amber-800">
                                <p class="font-medium">Produk belum memiliki gambar. Tambahkan gambar produk di bawah ini.</p>
                            </div>
                        @endif

                        <!-- Upload Gambar Baru -->
                        <div class="space-y-3">
                            <h4 class="text-xs font-semibold text-slate-600 uppercase tracking-wide">Tambah Gambar Baru</h4>
                            <div class="relative border-2 border-dashed border-slate-300 rounded-lg p-6 text-center hover:border-emerald-400 hover:bg-emerald-50 transition-colors cursor-pointer" 
                                onclick="document.getElementById('images-input').click()">
                                <svg class="mx-auto h-12 w-12 text-slate-400" stroke="currentColor" fill="none" viewBox="0 0 48 48"><path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-8l-3.172-3.172a4 4 0 00-5.656 0L28 20M12 20l3.172-3.172a4 4 0 015.656 0L28 20" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                                <p class="mt-2 text-sm text-slate-600"><span class="font-semibold text-emerald-600">Klik untuk upload</span> atau drag & drop</p>
                                <p class="text-xs text-slate-500 mt-1">PNG, JPG, GIF hingga 5MB</p>
                                <input type="file" id="images-input" name="images[]" class="hidden" accept="image/*" multiple onchange="handleImageSelect(this)" title="Pilih Gambar Produk" aria-label="Pilih Gambar Produk">
                            </div>

                            <div id="images-preview" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3"></div>
                        </div>
                        @error('images.*')
                            <p class="text-xs text-rose-600 mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status Produk -->
                    <div>
                        <label for="is_active" class="flex items-center gap-2.5 cursor-pointer group">
                            <input type="checkbox" id="is_active" name="is_active" value="1" class="w-5 h-5 rounded border-slate-300 text-emerald-600" {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                            <span class="text-sm font-medium text-slate-700 group-hover:text-slate-900">Produk Aktif</span>
                        </label>
                        <p class="text-xs text-slate-500 mt-1">Centang untuk membuat produk tampil di website.</p>
                    </div>

                    <!-- Action Buttons -->
                    <div class="pt-6 flex justify-end gap-3 border-t border-slate-200">
                        <a href="{{ route('admin.products.index') }}" class="btn-secondary text-xs">Batal</a>
                        <button type="submit" class="btn-primary text-xs">Update Produk</button>
                    </div>
                </form>

                @if($product->images->count() > 0)
                    @foreach($product->images as $image)
                        <form id="delete-image-form-{{ $image->id }}" action="{{ route('admin.products.delete-image', $image) }}" method="POST" class="hidden">
                            @csrf
                            @method('DELETE')
                        </form>
                    @endforeach
                @endif
            </div>
        </div>
    </div>

    <script>
        let selectedFiles = [];

        function handleImageSelect(input) {
            const files = Array.from(input.files);
            selectedFiles = [...selectedFiles, ...files];
            updatePreview();
        }

        function updatePreview() {
            const preview = document.getElementById('images-preview');
            preview.innerHTML = '';

            selectedFiles.forEach((file, index) => {
                const reader = new FileReader();
                reader.onload = (e) => {
                    const div = document.createElement('div');
                    div.className = 'relative group';
                    div.innerHTML = `
                        <div class="aspect-square rounded-lg overflow-hidden border border-slate-200 bg-slate-50">
                            <img src="${e.target.result}" alt="Preview" class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                <button type="button" class="bg-rose-600 text-white rounded-full p-2 hover:bg-rose-700" title="Hapus gambar pratinjau" aria-label="Hapus gambar pratinjau" onclick="removeImage(${index})">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                                </button>
                            </div>
                        </div>
                    `;
                    preview.appendChild(div);
                };
                reader.readAsDataURL(file);
            });
        }

        function removeImage(index) {
            selectedFiles.splice(index, 1);
            updatePreview();
            if (selectedFiles.length === 0) {
                document.getElementById('images-input').value = '';
            }
        }

        function setAsPrimary(event, imageId) {
            event.preventDefault();
            document.getElementById('primary_image_id').value = imageId;
            // Update UI
            document.querySelectorAll('[data-image-id]').forEach(el => {
                el.classList.remove('ring-2', 'ring-emerald-500');
            });
            
            // Find closest element with data-image-id
            const container = event.target.closest('[data-image-id]');
            if (container) {
                container.classList.add('ring-2', 'ring-emerald-500');
            }
        }
    </script>
@endsection