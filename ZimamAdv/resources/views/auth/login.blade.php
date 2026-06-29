@extends('layouts.frontend')

@section('title', 'Login - Zimam Advertising')

@section('content')
    <div class="card max-w-md mx-auto">
        <div class="card-header">
            <h2 class="text-base font-semibold">Login</h2>
        </div>
        <div class="card-body">
            @if($errors->any())
                <div class="mb-3 text-xs text-red-600">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('login.post') }}" method="POST" class="space-y-4 text-sm">
                @csrf
                <div>
                    <label class="form-label">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="form-input" required>
                </div>
                <div>
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-input" required>
                </div>
                <div class="space-y-2">
                    <label class="form-label">CAPTCHA</label>
                    <div class="flex items-center gap-3">
                        <div class="bg-slate-100 rounded-lg p-1 border border-slate-200 flex items-center justify-center h-12">
                            <img src="{{ route('captcha') }}" id="captcha-img" alt="Captcha" class="h-full rounded">
                        </div>
                        <button type="button" 
                                onclick="document.getElementById('captcha-img').src='{{ route('captcha') }}?t=' + new Date().getTime()" 
                                class="h-12 w-12 flex items-center justify-center rounded-lg border border-slate-200 text-slate-500 hover:text-emerald-600 hover:border-emerald-300 hover:bg-slate-50 transition-all duration-200 group" 
                                title="Segarkan CAPTCHA">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 group-hover:rotate-180 transition-transform duration-500">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                            </svg>
                        </button>
                    </div>
                    <input type="text" name="captcha" class="form-input w-full @error('captcha') border-red-500 focus:ring-red-500/20 focus:border-red-500 @enderror" placeholder="Masukkan kode CAPTCHA" required>
                    @error('captcha')
                        <span class="text-xs text-red-600 mt-1 block font-medium">{{ $message }}</span>
                    @enderror
                </div>
                <div class="flex items-center gap-2">
                    <input type="checkbox" name="remember" value="1" class="h-4 w-4 text-sky-600 border-slate-300 rounded">
                    <span class="text-xs text-slate-600">Ingat saya</span>
                </div>
                <button type="submit" class="btn-primary w-full">Login</button>
            </form>

            <p class="mt-4 text-xs text-center text-slate-600">Belum punya akun? <a href="{{ route('register') }}"
                    class="text-sky-700 font-medium">Daftar</a></p>
        </div>
    </div>
@endsection