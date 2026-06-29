@extends('layouts.admin')

@section('page_title', 'Edit Metode Pembayaran')

@section('content')
    <div class="w-full">
        <!-- Error Alert -->
        @if($errors->any())
            <div class="mb-6 p-4 bg-rose-50 border border-rose-200 rounded-lg">
                <div class="flex gap-3">
                    <svg class="flex-shrink-0 h-5 w-5 text-rose-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                            clip-rule="evenodd" />
                    </svg>
                    <div>
                        <h3 class="text-sm font-medium text-rose-800">Terjadi kesalahan</h3>
                        <ul class="mt-2 text-sm text-rose-700 space-y-1">
                            @foreach($errors->all() as $error)
                                <li>• {{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <div class="card">
            <div class="card-header">
                <span>Edit Metode Pembayaran</span>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.bank-accounts.update', $bankAccount) }}" method="POST"
                    enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="form-label">Jenis Metode <span class="text-rose-600">*</span></label>
                        <select name="payment_method_id"
                            class="form-select @error('payment_method_id') border-rose-500 @enderror" required>
                            @foreach($methods as $method)
                                <option value="{{ $method->id }}" {{ old('payment_method_id', $bankAccount->payment_method_id) == $method->id ? 'selected' : '' }}>
                                    Transfer Bank & Cicilan
                                </option>
                            @endforeach
                        </select>
                        @error('payment_method_id')
                            <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="form-label">Nama Bank <span class="text-rose-600">*</span></label>
                        <input type="text" name="bank_name" class="form-input @error('bank_name') border-rose-500 @enderror"
                            value="{{ old('bank_name', $bankAccount->bank_name) }}" required
                            placeholder="Contoh: Bank BCA, Bank Mandiri">
                        @error('bank_name')
                            <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="form-label">Nomor Rekening <span class="text-rose-600">*</span></label>
                        <input type="text" name="account_number"
                            class="form-input @error('account_number') border-rose-500 @enderror"
                            value="{{ old('account_number', $bankAccount->account_number) }}" required
                            placeholder="Nomor rekening bank">
                        @error('account_number')
                            <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="form-label">Nama Pemilik Rekening (Atas Nama) <span class="text-rose-600">*</span></label>
                        <input type="text" name="account_holder"
                            class="form-input @error('account_holder') border-rose-500 @enderror"
                            value="{{ old('account_holder', $bankAccount->account_holder) }}" required
                            placeholder="Nama pemilik rekening bank">
                        @error('account_holder')
                            <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="form-label">Logo Bank (Opsional)</label>
                        @if($bankAccount->image_path)
                            <div class="mb-3">
                                <p class="text-xs text-slate-500 mb-2">Gambar Saat Ini:</p>
                                <img src="{{ asset('storage/' . $bankAccount->image_path) }}" alt="QR/Logo"
                                    class="h-20 rounded border border-slate-200">
                            </div>
                        @endif
                        <div class="relative border-2 border-dashed border-slate-300 rounded-lg p-4 text-center hover:border-sky-400 hover:bg-sky-50 transition-colors cursor-pointer"
                            onclick="document.getElementById('image-input').click()">
                            <svg class="mx-auto h-8 w-8 text-slate-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                </path>
                            </svg>
                            <p class="mt-2 text-sm text-slate-600"><span class="font-semibold text-sky-600">Klik</span> atau
                                drag & drop</p>
                            <p class="text-xs text-slate-500 mt-1">PNG, JPG, GIF hingga 2MB</p>
                            <input type="file" id="image-input" name="image" class="hidden" accept="image/*"
                                onchange="previewImage(this)">
                        </div>
                        <div id="image-preview" class="mt-2"></div>
                        @error('image')
                            <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center pt-2">
                        <input type="checkbox" id="is_active" name="is_active" value="1"
                            class="w-4 h-4 rounded border-slate-300 text-emerald-600" {{ old('is_active', $bankAccount->is_active) ? 'checked' : '' }}>
                        <label for="is_active" class="ml-2.5 text-sm text-slate-700">Metode Aktif</label>
                    </div>

                    <div class="pt-2 flex justify-end gap-2 border-t border-slate-200">
                        <a href="{{ route('admin.bank-accounts.index') }}" class="btn-secondary text-xs">Batal</a>
                        <button type="submit" class="btn-primary text-xs">Update Metode</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function previewImage(input) {
            const preview = document.getElementById('image-preview');
            preview.innerHTML = '';

            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    const div = document.createElement('div');
                    div.className = 'relative inline-block';
                    div.innerHTML = `
                            <div class="inline-block rounded-lg overflow-hidden border border-slate-200">
                                <img src="${e.target.result}" alt="Preview" class="h-24 object-cover">
                            </div>
                            <button type="button" class="absolute -top-2 -right-2 bg-rose-600 text-white rounded-full p-1 hover:bg-rose-700" onclick="clearImage()">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                            </button>
                        `;
                    preview.appendChild(div);
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        function clearImage() {
            document.getElementById('image-input').value = '';
            document.getElementById('image-preview').innerHTML = '';
        }
    </script>
@endsection