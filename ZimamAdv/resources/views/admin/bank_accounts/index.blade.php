@extends('layouts.admin')

@section('page_title', 'Metode Pembayaran')

@section('content')
    @if(session('status'))
        <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl text-sm font-medium">
            {{ session('status') }}
        </div>
    @endif

    <!-- BAGIAN 1: METODE PEMBAYARAN UTAMA -->
    <div class="mb-8">
        <div class="mb-4">
            <h2 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                </svg>
                Metode Pembayaran Utama
            </h2>
            <div class="text-xs text-slate-500 mt-1">Aktifkan atau nonaktifkan metode pembayaran utama yang akan tampil di halaman checkout customer.</div>
        </div>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
            @foreach($paymentMethods as $method)
                <div class="bg-white border {{ $method->is_active ? 'border-emerald-200 shadow-emerald-500/5' : 'border-slate-200 bg-slate-50/50' }} rounded-2xl p-5 shadow-sm transition-all duration-300 hover:shadow-md">
                    <div class="flex flex-col h-full justify-between">
                        <div>
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $method->is_active ? 'bg-emerald-50 text-emerald-700 border border-emerald-100' : 'bg-slate-100 text-slate-500 border border-slate-200' }} mb-3">
                                <div class="w-1.5 h-1.5 rounded-full {{ $method->is_active ? 'bg-emerald-500 animate-pulse' : 'bg-slate-400' }}"></div>
                                {{ $method->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                            <h3 class="font-bold text-slate-800 text-base mb-1">{{ $method->name }}</h3>
                            <p class="text-[10px] text-slate-400 font-semibold mb-4 uppercase tracking-wider">Tipe: {{ $method->type }}</p>
                        </div>
                        <form action="{{ route('admin.payment-methods.toggle-status', $method) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full text-center py-2 px-3 rounded-xl text-xs font-bold transition-all duration-200 {{ $method->is_active ? 'bg-red-50 text-red-600 hover:bg-red-100 border border-red-100' : 'bg-emerald-50 text-emerald-600 hover:bg-emerald-100 border border-emerald-100' }}">
                                {{ $method->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- BAGIAN 2: DETAIL REKENING / KANAL PEMBAYARAN -->
    <div>
        <div class="mb-4 flex justify-between items-center">
            <div>
                <h2 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                    </svg>
                    Detail Rekening 
                </h2>
                <div class="text-xs text-slate-500 mt-1">Konfigurasi info rekening bank tujuan transfer manual untuk metode pembayaran Transfer Bank & Cicilan.</div>
            </div>
            <a href="{{ route('admin.bank-accounts.create') }}" class="btn-primary text-xs flex items-center gap-1.5 shadow-md shadow-emerald-500/20">
                Tambah Rekening
            </a>
        </div>

        <div class="table-wrapper bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
            <table class="table-default">
                <thead>
                    <tr>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Metode Pembayaran</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Nama Bank</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Nomor Rekening</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Atas Nama</th>
                        <th class="px-6 py-3.5 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider">Logo Bank</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3.5 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($bankAccounts as $account)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-slate-700">
                                {{ $account->method && $account->method->type === 'bank_transfer' ? 'Transfer Bank & Cicilan' : ($account->method->name ?? '-') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                                {{ $account->bank_name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-slate-800">
                                {{ $account->account_number ?: '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                                {{ $account->account_holder ?: '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                @if($account->image_path)
                                    <a href="{{ asset('storage/' . $account->image_path) }}" target="_blank" class="inline-block hover:scale-105 transition-transform">
                                        <img src="{{ asset('storage/' . $account->image_path) }}" alt="QR/Logo" class="h-8 w-auto rounded border border-slate-200 bg-white p-0.5">
                                    </a>
                                @else
                                    <span class="text-slate-400 text-xs italic">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $account->is_active ? 'bg-emerald-50 text-emerald-700 border border-emerald-100' : 'bg-slate-100 text-slate-500 border border-slate-200' }}">
                                    {{ $account->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                <div class="flex items-center justify-center gap-3">
                                    <a href="{{ route('admin.bank-accounts.edit', $account) }}" class="text-blue-600 hover:text-blue-900 transition-colors">Edit</a>
                                    <form action="{{ route('admin.bank-accounts.destroy', $account) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button class="text-rose-600 hover:text-rose-900 transition-colors font-medium" onclick="return confirm('Hapus channel ini?')">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-sm text-slate-400 py-8 italic bg-slate-50/20">
                                Belum ada konfigurasi rekening/kanal pembayaran. Silakan tambah channel di atas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- BAGIAN 3: PENGATURAN SYARAT & KETENTUAN CICILAN -->
    <div class="mt-8">
        <div class="mb-4">
            <h2 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                <svg class="w-5 h-5 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                </svg>
                Pengaturan Syarat Cicilan
            </h2>
            <div class="text-xs text-slate-500 mt-1">Konfigurasi batas minimal transaksi untuk opsi cicilan dan persentase DP yang wajib dibayar di muka.</div>
        </div>

        <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
            <form action="{{ route('admin.payment-methods.update-settings') }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @csrf
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Batas Transaksi Minimal (Rupiah)</label>
                    <div class="relative">
                        <input type="number" name="installment_min_amount" class="w-full pl-3 pr-3 py-2 border border-slate-200 rounded-xl focus:ring-blue-500 focus:border-blue-500 text-sm" value="{{ config('installments.min_amount') }}" required min="0">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Persentase Down Payment (DP %)</label>
                    <div class="relative">
                        <input type="number" name="installment_dp_percent" class="w-full pr-9 pl-3 py-2 border border-slate-200 rounded-xl focus:ring-blue-500 focus:border-blue-500 text-sm" value="{{ config('installments.dp_percent') }}" required min="0" max="100">
                        <span class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 text-sm">%</span>
                    </div>
                </div>

                <div class="md:col-span-2 flex justify-end pt-2 border-t border-slate-100">
                    <button type="submit" class="btn-primary text-xs shadow-md shadow-emerald-500/20">Simpan Pengaturan</button>
                </div>
            </form>
        </div>
    </div>
@endsection