@extends('layouts.frontend')

@section('title', 'Pembayaran - Zimam Advertising')

@section('content')
    <div class="max-w-5xl mx-auto">
        <!-- Header -->
        <div class="mb-8 pb-6 border-b border-slate-200">
            <div class="flex items-center gap-3 mb-2">
                <div
                    class="h-10 w-10 rounded-xl bg-gradient-to-br from-emerald-500 to-blue-600 flex items-center justify-center text-white shadow-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z">
                        </path>
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Detail Pembayaran</h1>
            </div>
            <p class="text-slate-500 text-sm ml-13">Selesaikan pembayaran untuk memproses pesanan Anda.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Ringkasan Card -->
            <div class="lg:col-span-1">
                <div class="card-premium relative overflow-hidden h-full">
                    <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-emerald-400 to-blue-500"></div>
                    <div class="p-6">
                        <h2 class="text-lg font-bold text-slate-800 mb-6 flex items-center gap-2">
                            <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4">
                                </path>
                            </svg>
                            Ringkasan Pesanan
                        </h2>

                        <div class="space-y-4">
                            <div class="flex flex-col">
                                <span class="text-xs text-slate-400 uppercase tracking-wider font-semibold mb-1">No.
                                    Pesanan</span>
                                <span class="font-bold text-slate-800">{{ $order->order_number }}</span>
                            </div>

                            <div class="flex flex-col">
                                <span class="text-xs text-slate-400 uppercase tracking-wider font-semibold mb-1">Total
                                    Tagihan</span>
                                <span
                                    class="font-black text-2xl text-transparent bg-clip-text bg-gradient-to-r from-emerald-600 to-blue-600">
                                    Rp {{ number_format($order->grand_total, 0, ',', '.') }}
                                </span>
                            </div>

                            <div class="flex flex-col pt-4 border-t border-slate-100">
                                <span class="text-xs text-slate-400 uppercase tracking-wider font-semibold mb-2">Status
                                    Pesanan</span>
                                <div>
                                    @if($order->payment_status == 'pending')
                                        <span
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold bg-amber-50 text-amber-600 border border-amber-200">
                                            <div class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></div>
                                            MENUNGGU PEMBAYARAN
                                        </span>
                                    @elseif(in_array($order->payment_status, ['sudah_dibayar', 'lunas']))
                                        <span
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold bg-emerald-50 text-emerald-600 border border-emerald-200">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                    d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            LUNAS
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold bg-slate-100 text-slate-600 border border-slate-200">
                                            {{ strtoupper($order->payment_status) }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            @if($payment && $payment->payment_proof_path)
                                <div
                                    class="mt-4 p-3 bg-emerald-50 border border-emerald-100 rounded-xl flex items-center justify-between">
                                    <span class="text-xs font-medium text-emerald-700">Bukti Terunggah</span>
                                    <a href="{{ asset('storage/' . $payment->payment_proof_path) }}" target="_blank"
                                        class="text-xs font-bold text-emerald-600 hover:text-emerald-800 underline">Lihat
                                        File</a>
                                </div>
                            @endif

                            @if(session('status'))
                                <div
                                    class="mt-4 p-3 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl text-sm font-medium">
                                    {{ session('status') }}
                                </div>
                            @endif
                            @if(session('error'))
                                <div
                                    class="mt-4 p-3 bg-rose-50 border border-rose-200 text-rose-700 rounded-xl text-sm font-medium">
                                    {{ session('error') }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Instruksi Card -->
            <div class="lg:col-span-2">
                <div class="card-premium p-6 sm:p-8 h-full flex flex-col">
                    @if(!$payment)
                        <div class="flex-1 flex flex-col items-center justify-center text-center p-8">
                            <div
                                class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center text-slate-400 mb-4">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <h3 class="font-bold text-slate-800 mb-1">Data Belum Tercatat</h3>
                            <p class="text-sm text-slate-500">Sistem sedang memproses data pembayaran Anda.</p>
                        </div>
                    @else
                        <div class="flex items-center justify-between mb-8 pb-6 border-b border-slate-100">
                            <div>
                                <p class="text-xs text-slate-400 uppercase tracking-wider font-semibold mb-1">Metode Terpilih
                                </p>
                                <p class="font-bold text-slate-800 text-lg uppercase">{{ $payment->method->name ?? '-' }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-xs text-slate-400 uppercase tracking-wider font-semibold mb-1">Status Transaksi
                                </p>
                                <p class="font-bold text-blue-600 uppercase">{{ str_replace('_', ' ', $payment->status) }}</p>
                            </div>
                        </div>
                        <div class="mb-6">
                        </div>

                        <div class="flex-1 flex flex-col justify-center">
                            @if($payment->method->type === 'installment')
                                <!-- Fitur Cicilan Profesional -->
                                <div class="space-y-6">
                                    <div class="bg-blue-50 border border-blue-100 rounded-2xl p-6">
                                        <div class="flex items-center gap-3 mb-4">
                                            <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center text-white">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                                            </div>
                                            <div>
                                                <h3 class="text-lg font-bold text-slate-800">Alur Cicilan ({{ config('installments.dp_percent') }}% DP + {{ 100 - config('installments.dp_percent') }}% Angsuran)</h3>
                                                <p class="text-xs text-slate-500">Silakan bayar DP terlebih dahulu untuk memproses pesanan.</p>
                                            </div>
                                        </div>

                                        @if($payment->status === 'belum_dibayar')
                                            <!-- Peringatan Bayar DP -->
                                            <div class="mb-6 p-5 bg-gradient-to-r from-blue-600 to-indigo-700 rounded-2xl shadow-lg shadow-blue-500/20 text-white relative overflow-hidden group">
                                                <div class="absolute -right-4 -top-4 w-24 h-24 bg-white/10 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700"></div>
                                                <div class="relative z-10">
                                                    <div class="flex justify-between items-start mb-4">
                                                        <div>
                                                            <p class="text-blue-100 text-[10px] font-bold uppercase tracking-widest mb-1">Tagihan Saat Ini</p>
                                                            <h4 class="text-2xl font-black">Down Payment ({{ config('installments.dp_percent') }}%)</h4>
                                                        </div>
                                                        <div class="text-right">
                                                            <p class="text-blue-100 text-[10px] font-bold uppercase tracking-widest mb-1">Total DP</p>
                                                            <p class="text-2xl font-black">Rp {{ number_format($payment->amount, 0, ',', '.') }}</p>
                                                        </div>
                                                    </div>
                                                    <p class="text-sm text-blue-50 mb-6 leading-relaxed">Harap melakukan transfer ke salah satu rekening di bawah dan unggah bukti pembayaran agar pesanan Anda dapat kami proses segera.</p>
                                                    <button onclick="openUploadModal(null, 'DP ({{ config('installments.dp_percent') }}%)', {{ (int) $payment->amount }})" class="w-full py-3 bg-white text-blue-700 rounded-xl font-bold text-sm shadow-xl shadow-blue-900/20 hover:bg-blue-50 transition-colors flex items-center justify-center gap-2">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                        Upload Bukti
                                                    </button>
                                                </div>
                                            </div>
                                        @elseif($payment->status === 'menunggu_verifikasi')
                                            <!-- Menunggu Verifikasi DP -->
                                            <div class="mb-6 p-5 bg-amber-50 border border-amber-200 rounded-2xl flex items-center gap-4">
                                                <div class="w-12 h-12 bg-amber-100 rounded-full flex items-center justify-center text-amber-600 flex-shrink-0 animate-pulse">
                                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                </div>
                                                <div>
                                                    <h4 class="font-bold text-amber-800">Bukti DP Sedang Diverifikasi</h4>
                                                    <p class="text-xs text-amber-700 leading-relaxed">Admin akan memeriksa bukti pembayaran Anda dalam waktu maksimal 1x24 jam. Pesanan akan mulai dikerjakan setelah status berubah menjadi 'Diterima'.</p>
                                                </div>
                                            </div>
                                        @endif

                                        <div class="bg-white border border-slate-200 rounded-xl overflow-hidden shadow-sm">
                                            <table class="w-full text-left text-sm">
                                                <thead class="bg-slate-50 text-slate-600 border-b border-slate-100">
                                                    <tr>
                                                        <th class="py-3 px-4 font-bold">Tahap</th>
                                                        <th class="py-3 px-4 font-bold">Nominal</th>
                                                        <th class="py-3 px-4 font-bold">Status</th>
                                                        <th class="py-3 px-4 font-bold text-right">Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="divide-y divide-slate-50">
                                                    <!-- DP Section -->
                                                    <tr class="{{ in_array($payment->status, ['lunas', 'sudah_dibayar', 'sebagian_dibayar', 'menunggu_verifikasi']) ? 'bg-slate-50/50' : 'bg-white' }}">
                                                        <td class="py-4 px-4">
                                                            <div class="font-bold text-slate-800">Down Payment ({{ config('installments.dp_percent') }}%)</div>
                                                            <div class="text-[10px] text-slate-400">Pembayaran Awal</div>
                                                        </td>
                                                        <td class="py-4 px-4 font-bold text-blue-600">
                                                            Rp {{ number_format($payment->amount, 0, ',', '.') }}
                                                        </td>
                                                        <td class="py-4 px-4">
                                                            @if(in_array($payment->status, ['lunas', 'sudah_dibayar', 'sebagian_dibayar']))
                                                                <span class="inline-flex items-center gap-1 px-2 py-1 rounded bg-emerald-100 text-emerald-700 text-[10px] font-bold">
                                                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                                                                    DITERIMA
                                                                </span>
                                                            @elseif($payment->status == 'menunggu_verifikasi')
                                                                <span class="inline-flex items-center px-2 py-1 rounded bg-blue-100 text-blue-700 text-[10px] font-bold">MENUNGGU VERIFIKASI</span>
                                                            @else
                                                                <span class="inline-flex items-center px-2 py-1 rounded bg-amber-100 text-amber-700 text-[10px] font-bold">BELUM BAYAR</span>
                                                            @endif
                                                        </td>
                                                        <td class="py-4 px-4 text-right">
                                                            @if(!in_array($payment->status, ['lunas', 'sudah_dibayar', 'sebagian_dibayar', 'menunggu_verifikasi']))
                                                                <button onclick="openUploadModal(null, 'DP ({{ config('installments.dp_percent') }}%)', {{ (int) $payment->amount }})" class="btn-primary py-1.5 px-3 text-[10px] font-bold uppercase tracking-wider">Upload Bukti</button>
                                                            @elseif($payment->payment_proof_path)
                                                                <a href="{{ asset('storage/' . $payment->payment_proof_path) }}" target="_blank" class="text-[10px] font-bold text-blue-600 hover:underline">Lihat Bukti</a>
                                                            @endif
                                                        </td>
                                                    </tr>

                                                    <!-- Installments Section -->
                                                    @foreach($payment->installments ?? [] as $installment)
                                                        <tr class="bg-white">
                                                            <td class="py-4 px-4">
                                                                <div class="font-bold text-slate-700">Cicilan Ke-{{ $installment->sequence }}</div>
                                                                <div class="text-[10px] text-slate-400">Jatuh Tempo: {{ \Carbon\Carbon::parse($installment->due_date)->format('d M Y') }}</div>
                                                            </td>
                                                            <td class="py-4 px-4 font-semibold text-slate-700">
                                                                Rp {{ number_format($installment->amount, 0, ',', '.') }}
                                                            </td>
                                                            <td class="py-4 px-4">
                                                                @if($installment->status == 'paid')
                                                                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded bg-emerald-100 text-emerald-700 text-[10px] font-bold">LUNAS</span>
                                                                @elseif($installment->status == 'menunggu_konfirmasi')
                                                                    <span class="inline-flex items-center px-2 py-1 rounded bg-blue-100 text-blue-700 text-[10px] font-bold">PROSES VERIFIKASI</span>
                                                                @elseif(\Carbon\Carbon::parse($installment->due_date)->isPast())
                                                                    <span class="inline-flex items-center px-2 py-1 rounded bg-rose-100 text-rose-700 text-[10px] font-bold">TERLAMBAT</span>
                                                                @else
                                                                    <span class="inline-flex items-center px-2 py-1 rounded bg-slate-100 text-slate-500 text-[10px] font-bold">PENDING</span>
                                                                @endif
                                                            </td>
                                                            <td class="py-4 px-4 text-right">
                                                                @if($installment->status == 'pending' || ($installment->status == 'overdue'))
                                                                    @if(in_array($payment->status, ['lunas', 'sudah_dibayar', 'sebagian_dibayar']))
                                                                        <button onclick="openUploadModal({{ $installment->id }}, 'Cicilan Ke-{{ $installment->sequence }}', {{ (int) $installment->amount }})" class="btn-primary py-1.5 px-3 text-[10px] font-bold uppercase tracking-wider">Upload Bukti</button>
                                                                    @else
                                                                        <span class="text-[9px] text-slate-400 italic">Tunggu DP Lunas</span>
                                                                    @endif
                                                                @elseif($installment->payment_proof_path)
                                                                    <a href="{{ asset('storage/' . $installment->payment_proof_path) }}" target="_blank" class="text-[10px] font-bold text-blue-600 hover:underline">Lihat Bukti</a>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <!-- Bank Accounts for Manual Transfer -->
                                    <div class="bg-white border border-slate-200 rounded-2xl p-6">
                                        <h4 class="font-bold text-slate-800 mb-4 flex items-center gap-2">
                                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                                            Rekening Pembayaran Bank
                                        </h4>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            @forelse($channels as $channel)
                                                <div class="p-4 bg-slate-50 rounded-xl border border-slate-100 relative group">
                                                    <div class="flex justify-between items-start mb-2">
                                                        <span class="text-xs font-bold text-blue-600 uppercase">{{ $channel->bank_name }}</span>
                                                        @if($channel->image_path)
                                                            <img src="{{ asset('storage/' . $channel->image_path) }}" class="h-6" alt="{{ $channel->bank_name }}">
                                                        @endif
                                                    </div>
                                                    <div class="text-lg font-black text-slate-800 tracking-wider mb-1 font-mono">{{ $channel->account_number }}</div>
                                                    <div class="text-xs text-slate-500 font-medium">A.N. {{ $channel->account_holder }}</div>
                                                    <button onclick="copyText('{{ $channel->account_number }}')" class="absolute bottom-4 right-4 text-slate-400 hover:text-blue-600 transition-colors">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6a2 2 0 002-2V7a2 2 0 00-2-2h-6a2 2 0 00-2 2z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                                    </button>
                                                </div>
                                            @empty
                                                <div class="col-span-2 p-4 bg-amber-50 border border-amber-200 text-amber-800 rounded-xl text-sm text-center">
                                                    Belum ada rekening pembayaran yang aktif. Silakan hubungi admin.
                                                </div>
                                            @endforelse
                                        </div>
                                    </div>
                                </div>
                            @elseif(in_array($payment->status, ['lunas', 'sudah_dibayar', 'sebagian_dibayar']))
                                <!-- Sukses Dibayar -->
                                <div
                                    class="bg-gradient-to-br from-emerald-50 to-teal-50 border border-emerald-200 rounded-2xl p-8 text-center my-auto">
                                    <div
                                        class="w-20 h-20 bg-emerald-500 rounded-full flex items-center justify-center mx-auto mb-6 shadow-xl shadow-emerald-500/40 transform scale-110">
                                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>
                                    <h3 class="text-2xl font-black text-slate-800 mb-2">Pembayaran Berhasil!</h3>
                                    <p class="text-slate-600 text-sm">Terima kasih, pembayaran Anda telah diverifikasi oleh sistem.
                                        Pesanan Anda segera diproses.</p>
                                    <div class="mt-6 flex flex-wrap justify-center gap-3">
                                        <a href="{{ route('chat.index') }}" class="btn-primary inline-flex items-center gap-2 py-3 px-6 text-base font-bold shadow-xl shadow-blue-500/30 transform transition hover:scale-105">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                                            Chat dengan Admin
                                        </a>
                                        <a href="{{ route('payments.invoice', $order->order_number) }}" target="_blank" class="inline-flex items-center gap-2 py-3 px-6 text-base font-bold text-slate-700 hover:text-slate-900 transition-colors bg-white rounded-xl border border-slate-200 transform transition hover:scale-105 shadow-sm">
                                            <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                            Download Nota
                                        </a>
                                    </div>
                                </div>
                            @elseif(in_array($payment->status, ['pending', 'belum_dibayar']))
                                @if($payment->snap_token)
                                    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                                    
                                    @if(in_array($payment->method->type, ['qris', 'dana', 'ovo', 'shopeepay']))
                                        <!-- Custom QRIS / E-Wallet Interface -->
                                        <div class="space-y-6 w-full">
                                             @if($payment->method->type === 'qris')
                                                <!-- GoPay QRIS Replica Payment Method -->
                                                <div class="bg-white border border-slate-200 rounded-3xl shadow-lg overflow-hidden max-w-md mx-auto">
                                                    <!-- Blue Header bar mirroring Midtrans Snap -->
                                                    <div class="bg-[#002e5a] text-white px-6 py-4 flex justify-between items-center">
                                                        <span class="font-bold text-sm">Toko Muhammad Nabil</span>
                                                        <button onclick="window.history.back()" class="text-white/80 hover:text-white transition-colors">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path>
                                                            </svg>
                                                        </button>
                                                    </div>

                                                    <!-- Amount and Order ID bar -->
                                                    <div class="px-6 py-4 bg-white border-b border-slate-100 flex justify-between items-center text-left">
                                                        <div>
                                                            <div class="flex items-center gap-1.5 mb-0.5">
                                                                <span class="text-2xl font-black text-slate-800">Rp {{ number_format($payment->amount, 0, ',', '.') }}</span>
                                                                <button onclick="copyToClipboard('{{ (int)$payment->amount }}', 'Nominal Pembayaran')" class="text-blue-500 hover:text-blue-700 transition-colors" title="Salin Nominal">
                                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6a2 2 0 002-2V7a2 2 0 00-2-2h-6a2 2 0 00-2 2z"></path>
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1"></path>
                                                                    </svg>
                                                                </button>
                                                            </div>
                                                            <div class="flex items-center gap-1">
                                                                <span class="text-xs text-slate-400 font-medium">Order ID #{{ $payment->reference }}</span>
                                                                <button onclick="copyToClipboard('{{ $payment->reference }}', 'Order ID')" class="text-blue-500 hover:text-blue-700 transition-colors" title="Salin Order ID">
                                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6a2 2 0 002-2V7a2 2 0 00-2-2h-6a2 2 0 00-2 2z"></path>
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1"></path>
                                                                    </svg>
                                                                </button>
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <button onclick="Swal.fire('Detail Pesanan', 'Nomor Pesanan: {{ $order->order_number }}<br>Total Tagihan: Rp {{ number_format($order->grand_total, 0, ',', '.') }}', 'info')" class="text-xs font-bold text-blue-600 flex items-center gap-0.5 hover:underline">
                                                                Details
                                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                                </svg>
                                                            </button>
                                                        </div>
                                                    </div>

                                                    <!-- Countdown Timer bar -->
                                                    <div class="bg-[#f0f4f7] py-2 text-center text-xs text-slate-600 font-bold border-b border-slate-100 flex items-center justify-center gap-1.5">
                                                        Pay within <span id="qris-timer" class="font-mono text-rose-600">00:10:00</span>
                                                    </div>

                                                    <!-- Gopay QRIS card area -->
                                                    <div class="p-6">
                                                        <!-- Title and Logos -->
                                                        <div class="flex justify-between items-center mb-6">
                                                            <span class="font-extrabold text-slate-700 text-base">GoPay QRIS</span>
                                                            <div class="flex items-center gap-2.5 select-none">
                                                                <!-- GoPay logo -->
                                                                <div class="flex items-center gap-1">
                                                                    <div class="w-3.5 h-3.5 rounded-full bg-[#00aed6]"></div>
                                                                    <span class="font-black text-slate-800 text-sm tracking-tighter">gopay</span>
                                                                </div>
                                                                <!-- GoPay Later logo -->
                                                                <div class="flex items-center gap-0.5">
                                                                    <span class="font-semibold text-slate-500 text-[10px] tracking-tight">gopay</span>
                                                                    <svg class="w-2.5 h-2.5 text-[#00aed6]" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                                                    </svg>
                                                                    <span class="font-black text-[#00aed6] text-[10px] tracking-tight">later</span>
                                                                </div>
                                                                <!-- QRIS Logo -->
                                                                <div class="bg-black text-white px-1.5 py-0.5 rounded text-[10px] font-black tracking-widest leading-none">
                                                                    QRIS
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- The actual QRIS Card layout -->
                                                        <div class="border border-slate-200 rounded-3xl p-6 bg-white shadow-sm relative overflow-hidden mb-6 flex flex-col items-center">
                                                            <!-- Red border strip like in screenshot -->
                                                            <div class="absolute left-0 top-1/4 bottom-1/4 w-1.5 bg-[#e21a1a] rounded-r"></div>

                                                            <div class="w-full flex justify-between items-center mb-4 border-b border-slate-100 pb-2">
                                                                <!-- QRIS Logo -->
                                                                <div class="text-left leading-none">
                                                                    <span class="font-black text-xl text-slate-800 tracking-tighter">QRIS</span>
                                                                    <span class="block text-[6px] text-slate-400 font-bold uppercase tracking-tight">GPN & Bank Indonesia</span>
                                                                </div>
                                                                <!-- GPN Logo -->
                                                                <div class="text-right flex items-center gap-0.5 bg-rose-50 border border-rose-100 rounded px-1.5 py-0.5 text-rose-600">
                                                                    <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 24 24">
                                                                        <path d="M12 2L2 22h20L12 2zm0 4l6.5 13H5.5L12 6z"/>
                                                                    </svg>
                                                                    <span class="font-black text-[8px] tracking-widest leading-none">GPN</span>
                                                                </div>
                                                            </div>

                                                            <!-- Merchant Name -->
                                                            <div class="text-center mb-4">
                                                                <h4 class="font-bold text-slate-800 text-sm">Toko Muhammad Nabil</h4>
                                                            </div>

                                                            <!-- QR Code Box -->
                                                            <div class="bg-white p-3 border border-slate-100 rounded-2xl inline-block shadow-sm mb-3">
                                                                <img id="qris-image-code" src="https://api.qrserver.com/v1/create-qr-code/?size=250x250&data={{ urlencode(route('payments.simulate-success', $payment->id)) }}" class="w-44 h-44 mx-auto" alt="QRIS Code">
                                                            </div>

                                                            <!-- Card footer -->
                                                            <div class="text-center text-[10px] text-slate-400 font-semibold">
                                                                Dicetak oleh: GoPay
                                                            </div>
                                                        </div>

                                                        <!-- How to pay link -->
                                                        <div class="text-left mb-6">
                                                            <a href="#" onclick="event.preventDefault(); Swal.fire('Cara Pembayaran QRIS', '1. Buka aplikasi e-wallet Anda (GoPay, OVO, DANA, ShopeePay, dll) atau mobile banking.<br>2. Pilih fitur Scan QR / Bayar.<br>3. Scan QR code yang tertera pada layar.<br>4. Periksa nominal pembayaran dan nama merchant (Toko Muhammad Nabil).<br>5. Masukkan PIN dan selesaikan pembayaran.<br>6. Klik tombol Check Status untuk memperbarui status pesanan.', 'question')" class="text-xs font-bold text-blue-600 hover:underline flex items-center gap-1.5">
                                                                <span class="w-4.5 h-4.5 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-[10px] font-bold">?</span>
                                                                How to pay
                                                            </a>
                                                        </div>

                                                        <!-- Action Buttons -->
                                                        <div class="space-y-3">
                                                            <button onclick="downloadQRIS()" class="w-full py-3.5 border border-slate-300 hover:bg-slate-50 text-slate-800 rounded-2xl font-bold text-sm transition-colors flex items-center justify-center gap-2">
                                                                Download QRIS
                                                            </button>
                                                            <button onclick="window.location.reload()" class="w-full py-3.5 bg-[#3c3d42] hover:bg-[#2c2d32] text-white rounded-2xl font-bold text-sm transition-colors flex items-center justify-center gap-2">
                                                                Check status
                                                            </button>
                                                            <!-- Fallback button for Midtrans Snap modal -->
                                                            <button id="pay-button" class="w-full py-2.5 text-blue-600 hover:text-blue-800 rounded-xl font-bold text-xs transition-colors flex items-center justify-center gap-2 mt-4 hover:bg-slate-50 border border-slate-100 border-dashed">
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                                                </svg>
                                                                Buka Midtrans Snap Popup
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            @else
                                                <!-- E-WALLET DETAILS DISPLAY -->
                                                @php
                                                    $walletColors = [
                                                        'dana' => ['from-sky-500 to-blue-600', 'DANA'],
                                                        'ovo' => ['from-purple-600 to-indigo-800', 'OVO'],
                                                        'shopeepay' => ['from-orange-500 to-red-600', 'ShopeePay'],
                                                    ];
                                                    $walletType = $payment->method->type;
                                                    $colorClass = $walletColors[$walletType][0] ?? 'from-blue-600 to-indigo-700';
                                                    $walletName = $walletColors[$walletType][1] ?? 'E-Wallet';
                                                @endphp
                                                <div class="bg-white border border-slate-200 rounded-3xl shadow-sm overflow-hidden p-6">
                                                    <div class="bg-gradient-to-r {{ $colorClass }} p-6 rounded-2xl text-white shadow-md relative overflow-hidden group">
                                                        <div class="absolute -right-4 -top-4 w-24 h-24 bg-white/10 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700"></div>
                                                        <div class="relative z-10 flex justify-between items-center">
                                                            <div>
                                                                <p class="text-white/80 text-[10px] font-bold uppercase tracking-widest mb-1">Nominal Transaksi</p>
                                                                <h4 class="text-2xl font-black">Rp {{ number_format($payment->amount, 0, ',', '.') }}</h4>
                                                            </div>
                                                            <div class="text-right">
                                                                <span class="text-lg font-black tracking-wider bg-white/20 px-3 py-1.5 rounded-lg border border-white/10 inline-block uppercase">{{ $walletName }}</span>
                                                            </div>
                                                        </div>
                                                        <div class="mt-4 pt-4 border-t border-white/10 flex justify-between text-xs text-white/80">
                                                            <span>Order ID: <b>{{ $payment->reference }}</b></span>
                                                            <span class="uppercase">Status: <b>{{ str_replace('_', ' ', $payment->status) }}</b></span>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="mt-6 p-4 bg-slate-50 border border-slate-100 rounded-2xl">
                                                        <h5 class="text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Instruksi Pembayaran</h5>
                                                        <p class="text-xs text-slate-500 leading-relaxed">
                                                            Pembayaran akan diproses via gateway Midtrans Sandbox. Setelah Anda menekan tombol "Bayar Sekarang", silakan selesaikan pembayaran pada popup yang muncul.
                                                        </p>
                                                    </div>
                                                    
                                                    <div class="mt-6">
                                                        <button id="pay-button" class="w-full btn-primary py-3 px-6 text-sm font-bold flex items-center justify-center gap-2 shadow-lg shadow-emerald-500/20 transform transition hover:scale-[1.01] active:scale-95">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                                            </svg>
                                                            Bayar dengan Midtrans Snap
                                                        </button>
                                                    </div>
                                                </div>
                                            @endif
                                            
                                            <!-- Demo Mode Simulation Card (Sandbox Only) -->
                                            @if(!config('services.midtrans.is_production'))
                                                <div class="p-6 bg-slate-50 text-slate-800 rounded-3xl border border-slate-200 shadow-sm relative overflow-hidden">
                                                    <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-blue-600/5 rounded-full blur-3xl"></div>
                                                    <div class="relative z-10">
                                                        <h4 class="text-xs font-bold tracking-widest uppercase text-slate-800 mb-2 flex items-center gap-1.5">
                                                            <span class="inline-block w-2 h-2 rounded-full bg-blue-500 animate-ping"></span>
                                                            Demo Mode (Sandbox)
                                                        </h4>
                                                        <p class="text-[11px] text-slate-600 mb-5 leading-relaxed">
                                                            Gunakan tombol simulasi di bawah ini untuk mensimulasikan hasil pembayaran sukses atau gagal.
                                                        </p>
                                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                                            <a href="{{ route('payments.simulate-success', $payment->id) }}" 
                                                               class="py-3 px-4 bg-emerald-600 hover:bg-emerald-700 text-white text-center font-bold text-[11px] uppercase tracking-wider rounded-xl shadow-lg shadow-emerald-600/25 hover:scale-[1.01] active:scale-95 transition-all flex items-center justify-center gap-1.5">
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                                                                </svg>
                                                                Simulasikan Pembayaran Berhasil
                                                            </a>
                                                            <a href="{{ route('payments.simulate-failure', $payment->id) }}" 
                                                               class="py-3 px-4 bg-rose-600 hover:bg-rose-700 text-white text-center font-bold text-[11px] uppercase tracking-wider rounded-xl shadow-lg shadow-rose-600/25 hover:scale-[1.01] active:scale-95 transition-all flex items-center justify-center gap-1.5">
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path>
                                                                </svg>
                                                                Simulasikan Pembayaran Gagal
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    @else
                                        <!-- Normal Midtrans / Snap Layout -->
                                        <div
                                            class="bg-gradient-to-br from-blue-50 to-emerald-50 border border-emerald-100 rounded-2xl p-8 text-center relative overflow-hidden">
                                            <div
                                                class="absolute -top-10 -right-10 w-32 h-32 bg-emerald-400 opacity-10 rounded-full blur-2xl">
                                            </div>
                                            <div
                                                class="absolute -bottom-10 -left-10 w-32 h-32 bg-blue-400 opacity-10 rounded-full blur-2xl">
                                            </div>

                                            <div class="relative z-10">
                                                <h3 class="text-2xl font-bold text-slate-800 mb-3">Selesaikan Pembayaran</h3>
                                                <p class="text-slate-600 mb-8 max-w-md mx-auto">Klik tombol di bawah ini untuk membuka
                                                    gerbang pembayaran Midtrans secara otomatis dan aman.</p>

                                                <button id="pay-button"
                                                    class="btn-primary py-4 px-10 text-lg shadow-xl shadow-emerald-500/30 font-bold flex items-center justify-center gap-3 mx-auto transform transition hover:scale-105">
                                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z">
                                                        </svg>
                                                        Bayar Sekarang
                                                    </button>
                                                    <div id="no-channels-alert"
                                                        class="hidden mt-4 p-4 bg-amber-50 border border-amber-200 text-amber-800 rounded-lg text-sm">
                                                        <div class="font-semibold">Tidak ada kanal pembayaran tersedia</div>
                                                        <div class="mt-2">Silakan hubungi <strong>Toko Muhammad Nabil</strong> untuk membahas
                                                            prosedur pembayaran.</div>
                                                        <div class="mt-3 flex items-center gap-2">
                                                            <a href="{{ route('chat.index') }}" class="btn-primary">Hubungi Admin</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                    @endif
                                    
                                    <p class="text-xs text-slate-500 text-center mt-6 flex items-center justify-center gap-2">
                                        <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                                            </path>
                                        </svg>
                                        Didukung oleh sistem keamanan Midtrans
                                    </p>
                                @elseif($payment->method->type === 'bank_transfer')
                                    <!-- Transfer Bank Manual -->
                                    <div class="bg-white border border-slate-200 rounded-2xl p-6 sm:p-8">
                                        <h3 class="text-xl font-bold text-slate-800 mb-4">Transfer Bank Manual</h3>
                                        <p class="text-slate-600 text-sm mb-6">Silakan transfer sesuai dengan total tagihan ke rekening berikut:</p>
 
                                        <div class="space-y-4 mb-6">
                                            @forelse($channels as $channel)
                                                <div class="bg-slate-50 border border-slate-200 rounded-xl p-4 relative group">
                                                    <div class="flex justify-between items-start mb-2">
                                                        <span class="text-sm font-semibold text-slate-700">{{ $channel->bank_name }}</span>
                                                        @if($channel->image_path)
                                                            <img src="{{ asset('storage/' . $channel->image_path) }}" class="h-6" alt="{{ $channel->bank_name }}">
                                                        @endif
                                                    </div>
                                                    <p class="text-lg font-bold text-slate-900 tracking-wider font-mono">{{ $channel->account_number }}</p>
                                                    <p class="text-xs text-slate-500 uppercase mt-1">A.N. {{ $channel->account_holder }}</p>
                                                    <button onclick="copyText('{{ $channel->account_number }}')" class="absolute bottom-4 right-4 text-slate-400 hover:text-emerald-600 transition-colors">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6a2 2 0 002-2V7a2 2 0 00-2-2h-6a2 2 0 00-2 2z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                                    </button>
                                                </div>
                                            @empty
                                                <div class="p-4 bg-amber-50 border border-amber-200 text-amber-800 rounded-xl text-sm text-center">
                                                    Belum ada rekening pembayaran yang aktif. Silakan hubungi admin.
                                                </div>
                                            @endforelse
                                        </div>
 
                                        <form action="{{ route('payments.upload-proof', $order->order_number) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                                            @csrf
                                            <div>
                                                <label class="block text-sm font-semibold text-slate-700 mb-2">Upload Bukti Transfer</label>
                                                <input type="file" name="payment_proof" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100" accept="image/*" required>
                                            </div>
                                            <button type="submit" class="btn-primary w-full py-3">Kirim Bukti Pembayaran</button>
                                        </form>
                                    </div>
                                @elseif(in_array($payment->method->type, ['tunai', 'cod', 'cash']))
                                    <!-- Bayar di Toko (Zimam Advertising) -->
                                    <div class="bg-gradient-to-br from-blue-50 to-indigo-50 border border-blue-200 rounded-3xl p-8 text-center my-auto shadow-sm">
                                        <div class="w-20 h-20 bg-white rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-md text-blue-600 transform -rotate-3 hover:rotate-0 transition-transform">
                                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            </svg>
                                        </div>
                                        <h3 class="text-2xl font-black text-slate-800 mb-2">Bayar tunai di toko</h3>
                                        <p class="text-slate-500 text-sm mb-8 max-w-sm mx-auto uppercase tracking-widest font-bold">kami tunggu ya kedatangannya</p>

                                        <div class="bg-white/80 backdrop-blur-sm rounded-2xl p-6 mb-8 text-left border border-white shadow-inner">
                                            <div class="space-y-4">
                                                <div class="flex items-start gap-3">
                                                    <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center text-blue-600 shrink-0">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                                    </div>
                                                    <div>
                                                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Toko</p>
                                                        <p class="text-sm font-black text-slate-700">Zimam Advertising</p>
                                                    </div>
                                                </div>
                                                <div class="flex items-start gap-3">
                                                    <div class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center text-emerald-600 shrink-0">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                                                    </div>
                                                    <div>
                                                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Alamat</p>
                                                        <p class="text-sm font-bold text-slate-700">Jl. Pemda, Pangkalan Kerinci Kota, Kec. Pangkalan Kerinci, Kabupaten Pelalawan, Riau</p>
                                                    </div>
                                                </div>
                                                <div class="flex items-start gap-3">
                                                    <div class="w-8 h-8 bg-amber-100 rounded-lg flex items-center justify-center text-amber-600 shrink-0">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                    </div>
                                                    <div>
                                                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Jam Operasional</p>
                                                        <p class="text-sm font-bold text-slate-700">08:00 - 21:00 WIB (Senin - Sabtu)</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="flex flex-col gap-3">
                                            <a href="https://www.google.com/maps/place/Zimam+Advertising/@0.4077919,101.8537087,754m/data=!3m2!1e3!4b1!4m6!3m5!1s0x31d5c5005d9126ad:0x8ecbf06ad9204431!8m2!3d0.4077919!4d101.8562836!16s%2Fg%2F11vyg1qs16?entry=ttu&g_ep=EgoyMDI2MDUwNi4wIKXMDSoASAFQAw%3D%3D" target="_blank" class="w-full btn-primary py-4 text-base font-black flex items-center justify-center gap-2 shadow-xl shadow-blue-600/20 transform transition hover:scale-[1.02] active:scale-95">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path></svg>
                                                Buka di Google Maps
                                            </a>
                                            <a href="{{ route('orders.index') }}" class="w-full py-4 text-sm font-bold text-slate-500 hover:text-slate-800 transition-colors bg-slate-100 rounded-2xl border border-slate-200">
                                                Tutup & Kembali ke Pesanan
                                            </a>
                                        </div>
                                    </div>
                                @else
                                    <!-- Token Gagal Dibuat / Network Error -->
                                    <div class="bg-rose-50 border border-rose-200 rounded-2xl p-6 text-center my-auto">
                                        <div
                                            class="w-16 h-16 bg-white rounded-full flex items-center justify-center mx-auto mb-4 shadow-sm">
                                            <svg class="w-8 h-8 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                                </path>
                                            </svg>
                                        </div>
                                        <h3 class="text-lg font-bold text-rose-800 mb-2">Sistem Pembayaran Sedang Gangguan</h3>
                                        <p class="text-rose-600 text-sm mb-4">Sistem gagal terhubung ke server Midtrans (Connection Timeout). Hal ini biasanya disebabkan oleh jaringan internet atau server *payment gateway* sedang sibuk.</p>
                                        <p class="text-slate-600 text-sm">Silakan coba sambungkan ulang pembayaran, atau ganti metode pembayaran. Jika tetap terkendala, hubungi Admin untuk prosedur pembayaran manual.</p>
                                        <div class="mt-4 flex flex-wrap items-center justify-center gap-2">
                                            <form method="POST" action="{{ route('payments.retry-gateway', $order->order_number) }}">
                                                @csrf
                                                <button type="submit" class="btn-primary">Coba Lagi Pembayaran</button>
                                            </form>
                                            <a href="{{ route('chat.index') }}" class="btn-ghost">Hubungi Admin</a>
                                        </div>
                                    </div>
                                @endif
                            @endif
                        </div>

                                <!-- Modal Upload Proof -->
                                <div id="upload-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
                                    <div class="bg-white rounded-2xl w-full max-w-md shadow-2xl overflow-hidden animate-fade-in">
                                        <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50">
                                            <h5 class="font-bold text-slate-800" id="modal-title">Upload Bukti Pembayaran</h5>
                                            <button onclick="closeUploadModal()" class="text-slate-400 hover:text-slate-600">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                            </button>
                                        </div>
                                        <form action="{{ route('payments.upload-proof', $order->order_number) }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
                                            @csrf
                                            <input type="hidden" name="installment_id" id="modal-installment-id">

                                            <div class="p-3 bg-blue-50 rounded-lg flex justify-between items-center">
                                                <span class="text-xs font-medium text-blue-700" id="modal-item-name">Pembayaran</span>
                                                <span class="font-bold text-blue-800" id="modal-amount">Rp 0</span>
                                            </div>

                                            <div>
                                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Pilih File Bukti (JPG/PNG)</label>
                                                <div class="relative border-2 border-dashed border-slate-200 rounded-xl p-4 hover:border-blue-400 transition-colors cursor-pointer group">
                                                    <input type="file" name="payment_proof" class="absolute inset-0 opacity-0 cursor-pointer" accept="image/*" required onchange="handleFileSelect(this)">
                                                    <div class="text-center" id="file-placeholder">
                                                        <svg class="w-8 h-8 text-slate-400 mx-auto mb-2 group-hover:text-blue-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                        <span class="text-xs text-slate-500">Klik atau seret gambar ke sini</span>
                                                    </div>
                                                    <div id="file-preview" class="hidden text-center">
                                                        <span class="text-xs text-emerald-600 font-bold flex items-center justify-center gap-1">
                                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                                                            File terpilih!
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div>
                                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Catatan (Opsional)</label>
                                                <textarea name="note" rows="2" class="w-full border-slate-200 rounded-xl text-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Misal: Pembayaran dari rekening A.N. Budi..."></textarea>
                                            </div>

                                            <button type="submit" class="w-full btn-primary py-3 font-bold shadow-lg shadow-blue-500/30">Kirim Pembayaran</button>
                                        </form>
                                    </div>
                                </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@if($payment)
    @push('scripts')
                    @php
        $midtransScript = config('services.midtrans.is_production') ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js';
                    @endphp
                    <script>
                        function openUploadModal(installmentId, itemName, amount) {
                            document.getElementById('modal-installment-id').value = installmentId;
                            document.getElementById('modal-item-name').textContent = 'Pembayaran: ' + itemName;
                            document.getElementById('modal-amount').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(amount);
                            document.getElementById('upload-modal').classList.remove('hidden');
                            document.body.style.overflow = 'hidden';
                        }

                        function closeUploadModal() {
                            document.getElementById('upload-modal').classList.add('hidden');
                            document.body.style.overflow = 'auto';
                        }

                        function handleFileSelect(input) {
                            const placeholder = document.getElementById('file-placeholder');
                            const preview = document.getElementById('file-preview');
                            if (input.files && input.files[0]) {
                                placeholder.classList.add('hidden');
                                preview.classList.remove('hidden');
                            }
                        }

                        function copyText(text) {
                            navigator.clipboard.writeText(text).then(() => {
                                alert('Nomor rekening berhasil disalin!');
                            });
                        }

                        function copyToClipboard(text, label) {
                            navigator.clipboard.writeText(text).then(() => {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil Disalin',
                                    text: label + ' berhasil disalin ke clipboard!',
                                    timer: 1500,
                                    showConfirmButton: false
                                });
                            });
                        }

                        function downloadQRIS() {
                            const qrImage = document.getElementById('qris-image-code');
                            if (!qrImage) return;
                            const link = document.createElement('a');
                            link.href = qrImage.src;
                            link.download = 'QRIS_PAY_{{ $payment->reference }}.png';
                            link.target = '_blank';
                            document.body.appendChild(link);
                            link.click();
                            document.body.removeChild(link);
                        }

                        document.addEventListener('DOMContentLoaded', function () {
                            const timerEl = document.getElementById('qris-timer');
                            if (!timerEl) return;

                            let countdownKey = 'qris_countdown_end_{{ $payment->id }}';
                            let endTime = sessionStorage.getItem(countdownKey);

                            if (!endTime) {
                                endTime = Date.now() + 600 * 1000; // 10 minutes
                                sessionStorage.setItem(countdownKey, endTime);
                            } else {
                                endTime = parseInt(endTime, 10);
                            }

                            function updateTimer() {
                                let now = Date.now();
                                let diff = endTime - now;

                                if (diff <= 0) {
                                    timerEl.textContent = '00:00:00';
                                    clearInterval(timerInterval);
                                    return;
                                }

                                let hours = Math.floor(diff / (1000 * 60 * 60));
                                let minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                                let seconds = Math.floor((diff % (1000 * 60)) / 1000);

                                let hoursStr = String(hours).padStart(2, '0');
                                let minutesStr = String(minutes).padStart(2, '0');
                                let secondsStr = String(seconds).padStart(2, '0');

                                timerEl.textContent = `${hoursStr}:${minutesStr}:${secondsStr}`;
                            }

                            updateTimer();
                            let timerInterval = setInterval(updateTimer, 1000);
                        });
                    </script>
                    @if($payment->snap_token)
                            <script src="{{ $midtransScript }}" data-client-key="{{ config('services.midtrans.client_key') }}"></script>
                            <script>
                                document.addEventListener('DOMContentLoaded', function () {
                                    var btn = document.getElementById('pay-button');
                                    if (!btn) return;

                                    btn.addEventListener('click', function (e) {
                                        e.preventDefault();
                                        try {
                                            if (typeof snap === 'undefined' || typeof snap.pay !== 'function') {
                                                alert('Plugin pembayaran belum siap. Silakan coba lagi.');
                                                return;
                                            }

                                        snap.pay('{{ $payment->snap_token }}', {

                                                onSuccess: function (result) {

                                                    Swal.fire({
                                                        icon: 'success',
                                                        title: 'Pembayaran Berhasil',
                                                        text: 'Pesanan Anda sedang diproses.',
                                                        confirmButtonColor: '#10b981'
                                                    }).then(() => {
                                                        window.location.reload();
                                                    });

                                                },

                                                onPending: function (result) {
                                                    window.location.reload();
                                                },

                                                onError: function (result) {

                                                    console.error(result);

                                                    let message = 'Terjadi kesalahan pembayaran';

                                                    try {

                                                        var msg = result?.status_message?.toLowerCase() || '';

                                                        if (
                                                            msg.includes('no payment channels')
                                                        ) {

                                                            var el = document.getElementById('no-channels-alert');

                                                            if (el) {
                                                                el.classList.remove('hidden');
                                                            }

                                                            return;
                                                        }

                                                        message = result.status_message;

                                                    } catch (e) { }

                                                    Swal.fire({
                                                        icon: 'error',
                                                        title: 'Pembayaran Gagal',
                                                        text: message,
                                                        confirmButtonColor: '#ef4444'
                                                    });

                                                },

                                                onClose: function () {

                                                    Swal.fire({
                                                        icon: 'warning',
                                                        title: 'Pembayaran Belum Selesai',
                                                        text: 'Anda menutup popup pembayaran.',
                                                        confirmButtonColor: '#f59e0b'
                                                    });

                                                }

                                            });
                                    } catch (err) {
                                        console.error('Midtrans snap error', err);
                                        alert('Terjadi kesalahan saat membuka gerbang pembayaran. Coba lagi nanti.');
                                    }
                                });
                            });
                        </script>
                    @endif
    @endpush
@endif