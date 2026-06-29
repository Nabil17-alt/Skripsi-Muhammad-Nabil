@extends('layouts.admin')

@section('page_title', 'Edit Akun')

@section('content')
    <div class="w-full">
        <!-- Error Alert -->
        @if($errors->any())
            <div class="mb-6 p-4 bg-rose-50 border border-rose-200 rounded-lg">
                <div class="flex gap-3">
                    <svg class="flex-shrink-0 h-5 w-5 text-rose-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
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
                <span>Edit Data Akun</span>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.users.update', $user) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="form-label">Nama <span class="text-rose-600">*</span></label>
                        <input type="text" name="name" class="form-input @error('name') border-rose-500 @enderror" 
                            value="{{ old('name', $user->name) }}" required placeholder="Nama lengkap">
                        @error('name')
                            <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="form-label">Email <span class="text-rose-600">*</span></label>
                        <input type="email" name="email" class="form-input @error('email') border-rose-500 @enderror" 
                            value="{{ old('email', $user->email) }}" required placeholder="user@example.com">
                        @error('email')
                            <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="form-label">No. HP</label>
                        <input type="text" name="phone" class="form-input @error('phone') border-rose-500 @enderror" 
                            value="{{ old('phone', $user->phone) }}" placeholder="08123456789">
                        @error('phone')
                            <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="form-label">Role <span class="text-rose-600">*</span></label>
                        <select name="role_id" class="form-select @error('role_id') border-rose-500 @enderror" required>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}" {{ old('role_id', $user->role_id) == $role->id ? 'selected' : '' }}>
                                    {{ $role->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('role_id')
                            <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="form-label">Password Baru (Opsional)</label>
                        <input type="password" name="password" class="form-input @error('password') border-rose-500 @enderror" 
                            placeholder="••••••••">
                        <p class="text-xs text-slate-500 mt-1">Kosongkan jika tidak ingin mengubah password</p>
                        @error('password')
                            <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center pt-2">
                        <input type="checkbox" id="is_active" name="is_active" value="1" class="w-4 h-4 rounded border-slate-300 text-emerald-600" {{ old('is_active', $user->is_active) ? 'checked' : '' }}>
                        <label for="is_active" class="ml-2.5 text-sm text-slate-700">Akun Aktif</label>
                    </div>

                    @if(($user->role->name ?? '') === 'customer')
                    <div>
                        <label class="form-label">Tenor Cicilan yang Diizinkan <span class="text-rose-600">*</span></label>
                        <select name="allowed_tenor" id="allowed_tenor" class="form-select @error('allowed_tenor') border-rose-500 @enderror" required>
                            <option value="3" {{ old('allowed_tenor', $user->allowed_tenor) == 3 ? 'selected' : '' }}>3 Bulan</option>
                            <option value="6" {{ old('allowed_tenor', $user->allowed_tenor) == 6 ? 'selected' : '' }}>6 Bulan</option>
                            <option value="12" {{ old('allowed_tenor', $user->allowed_tenor) == 12 ? 'selected' : '' }}>12 Bulan</option>
                        </select>
                        @error('allowed_tenor')
                            <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    @else
                        <input type="hidden" name="allowed_tenor" value="3">
                    @endif

                    <div class="pt-2 flex justify-end gap-2 border-t border-slate-200">
                        <a href="{{ route('admin.users.index') }}" class="btn-secondary text-xs">Batal</a>
                        <button type="submit" class="btn-primary text-xs">Update Akun</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection