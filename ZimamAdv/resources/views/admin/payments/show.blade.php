@extends('layouts.admin')

@section('page_title', 'Detail Pembayaran')

@section('content')
    <div class="w-full space-y-6">
        <!-- Header Info -->
        <div class="card">
            <div class="card-header">
                <div>
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1">Pesanan</p>
                    <h2 class="text-2xl font-bold text-slate-900">#{{ $payment->order->order_number ?? '-' }}</h2>
                </div>
            </div>
            <div class="card-body grid grid-cols-2 md:grid-cols-4 gap-4">
                <div>
                    <p class="text-xs text-slate-500 uppercase tracking-wide font-semibold mb-1">Metode</p>
                    <p class="text-sm font-medium text-slate-900">{{ $payment->method->name ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-slate-500 uppercase tracking-wide font-semibold mb-1">Status</p>
                    @php
                        $statusBadges = [
                            'belum_dibayar' => ['bg-amber-50', 'text-amber-700', 'Menunggu'],
                            'pending' => ['bg-amber-50', 'text-amber-700', 'Menunggu'],
                            'lunas' => ['bg-emerald-50', 'text-emerald-700', 'Lunas'],
                            'sudah_dibayar' => ['bg-emerald-50', 'text-emerald-700', 'Lunas'],
                            'sebagian_dibayar' => ['bg-emerald-50', 'text-emerald-700', 'Sebagian Dibayar'],
                            'gagal' => ['bg-rose-50', 'text-rose-700', 'Gagal'],
                            'failed' => ['bg-rose-50', 'text-rose-700', 'Gagal'],
                            'expire' => ['bg-slate-100', 'text-slate-700', 'Kadaluarsa'],
                            'expired' => ['bg-slate-100', 'text-slate-700', 'Kadaluarsa'],
                        ];
                        $badge = $statusBadges[$payment->status] ?? ['bg-slate-50', 'text-slate-700', ucfirst(str_replace('_', ' ', $payment->status))];
                    @endphp
                    <span class="badge-status {{ $badge[0] }} {{ $badge[1] }}">{{ $badge[2] }}</span>
                </div>
                <div>
                    <p class="text-xs text-slate-500 uppercase tracking-wide font-semibold mb-1">Jumlah</p>
                    <p class="text-lg font-bold text-sky-600">Rp {{ number_format($payment->amount, 0, ',', '.') }}</p>
                </div>
                <div>
                    <p class="text-xs text-slate-500 uppercase tracking-wide font-semibold mb-1">Tanggal</p>
                    <p class="text-sm font-medium text-slate-900">{{ $payment->created_at->format('d M Y') }}</p>
                </div>
            </div>
        </div>

        <!-- Informasi Customer & Pesanan -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Customer -->
            <div class="card card-body">
                <h3 class="text-sm font-semibold text-slate-700 mb-4">Customer</h3>
                <div class="space-y-3">
                    <div>
                        <p class="text-xs text-slate-500 uppercase tracking-wide font-semibold mb-0.5">Nama</p>
                        <p class="text-sm font-medium text-slate-900">{{ $payment->order->user->name ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 uppercase tracking-wide font-semibold mb-0.5">Email</p>
                        <p class="text-sm text-slate-700">{{ $payment->order->user->email ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 uppercase tracking-wide font-semibold mb-0.5">Telepon</p>
                        <p class="text-sm text-slate-700">{{ $payment->order->user->phone ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <!-- Pesanan -->
            <div class="card card-body">
                <h3 class="text-sm font-semibold text-slate-700 mb-4">Ringkasan Pesanan</h3>
                <div class="space-y-3">
                    <div class="flex justify-between items-center py-2 border-b border-slate-200">
                        <span class="text-sm text-slate-600">Tanggal Pesanan</span>
                        <span
                            class="font-medium text-slate-900">{{ $payment->order->created_at?->format('d M Y H:i') ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-slate-200">
                        <span class="text-sm text-slate-600">Total Pesanan</span>
                        <span class="font-bold text-sky-600">Rp
                            {{ number_format($payment->order->grand_total ?? 0, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2">
                        <span class="text-sm text-slate-600">Dibayarkan</span>
                        <span class="font-bold text-emerald-600">Rp
                            {{ number_format($payment->amount, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bukti Pembayaran Manual -->
        @if($payment->payment_proof_path)
            <div class="card">
                <div class="card-header bg-amber-50">
                    <span class="text-sm font-bold text-amber-800 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Bukti Pembayaran (DP / Full)
                    </span>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <img src="{{ asset('storage/' . $payment->payment_proof_path) }}" alt="Bukti Bayar" class="max-w-full h-auto rounded-lg shadow-sm border border-slate-200">
                    </div>
                    @if($payment->notes)
                        <div class="p-3 bg-slate-50 rounded border border-slate-100">
                            <p class="text-xs text-slate-500 uppercase font-bold mb-1 text-[10px]">Catatan Pengguna:</p>
                            <p class="text-sm text-slate-700 italic">"{{ $payment->notes }}"</p>
                        </div>
                    @endif
                    <div class="mt-4">
                        <a href="{{ asset('storage/' . $payment->payment_proof_path) }}" target="_blank" class="text-sky-600 hover:underline text-xs font-bold">Buka Gambar Penuh (Tab Baru)</a>
                    </div>
                </div>
            </div>
        @endif

        <!-- Referensi Gateway -->
        @if($payment->reference)
            <div class="card card-body">
                <p class="text-xs text-slate-500 uppercase tracking-wide font-semibold mb-2">Referensi Gateway</p>
                <div class="flex items-center justify-between bg-slate-50 rounded p-3">
                    <code class="text-xs font-mono text-slate-700 break-all">{{ $payment->reference }}</code>
                    <button onclick="copyText('{{ $payment->reference }}')"
                        class="text-sky-600 hover:text-sky-700 text-xs font-semibold">
                        Salin
                    </button>
                </div>
            </div>
        @endif

        <!-- Cicilan (jika ada) -->
        @if($payment->method && $payment->method->type === 'installment')
            <div class="card">
                <div class="card-header flex justify-between items-center">
                    <span class="font-bold">Rincian Cicilan ({{ $payment->installments->count() }} Tahap)</span>
                    <span class="text-[10px] bg-blue-100 text-blue-700 px-2 py-1 rounded font-bold uppercase">Manual Bank Transfer</span>
                </div>
                <div class="card-body">
                    @if($payment->installments && $payment->installments->count())
                        <div class="overflow-x-auto">
                            <table class="table-default text-xs w-full">
                                <thead>
                                    <tr class="bg-slate-50">
                                        <th class="py-2">Tahap</th>
                                        <th class="py-2">Jatuh Tempo</th>
                                        <th class="py-2 text-right">Jumlah</th>
                                        <th class="py-2">Status</th>
                                        <th class="py-2">Bukti</th>
                                        <th class="py-2 text-right">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @foreach($payment->installments as $inst)
                                        <tr>
                                            <td class="font-bold text-slate-700">Bulan {{ $inst->sequence }}</td>
                                            <td>{{ $inst->due_date->format('d M Y') }}</td>
                                            <td class="text-right font-bold text-slate-900">Rp {{ number_format($inst->amount, 0, ',', '.') }}</td>
                                            <td>
                                                @if($inst->status === 'paid')
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-700">LUNAS</span>
                                                @elseif($inst->status === 'menunggu_konfirmasi')
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-blue-100 text-blue-700">MENUNGGU</span>
                                                @else
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-500">PENDING</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($inst->payment_proof_path)
                                                    <a href="{{ asset('storage/' . $inst->payment_proof_path) }}" target="_blank" class="text-sky-600 hover:underline font-bold">Lihat File</a>
                                                @else
                                                    <span class="text-slate-300 italic">Belum ada</span>
                                                @endif
                                            </td>
                                            <td class="text-right">
                                                @if($inst->status !== 'paid')
                                                    <form action="{{ route('admin.payments.installments.paid', [$payment, $inst->sequence]) }}" method="POST" class="inline">
                                                        @csrf
                                                        <button type="submit" class="btn-primary py-1 px-2 text-[10px]" onclick="return confirm('Verifikasi pembayaran cicilan ke-{{ $inst->sequence }}?')">
                                                            Tandai Lunas
                                                        </button>
                                                    </form>
                                                @else
                                                    <span class="text-emerald-600">
                                                        <svg class="w-4 h-4 ml-auto" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                                    </span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        <!-- Action Buttons -->
        <div class="flex justify-end gap-3">
            <a href="{{ route('admin.payments.index') }}" class="btn-secondary text-xs">Kembali</a>
            @if(!in_array($payment->status, ['lunas', 'sudah_dibayar', 'sebagian_dibayar']))
                <form action="{{ route('admin.payments.verify', $payment) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="btn-primary text-xs"
                        onclick="return confirm('Konfirmasi pembayaran ini?')">
                        {{ $payment->method && $payment->method->type === 'installment' ? 'Verifikasi DP' : 'Tandai Lunas' }}
                    </button>
                </form>
                @if(in_array($payment->method->type ?? null, ['qris', 'ewallet']))
                    <form action="{{ route('payments.simulate-auto', $payment) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit"
                            class="inline-flex items-center justify-center rounded-md bg-slate-600 px-4 py-2 text-xs font-semibold text-white shadow-sm hover:bg-slate-700 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-slate-500 focus-visible:ring-offset-2"
                            onclick="return confirm('Simulasikan callback dari payment gateway?')">
                            Simulasi Callback
                        </button>
                    </form>
                @endif
            @endif
        </div>
    </div>

    <script>
        function copyText(text) {
            navigator.clipboard.writeText(text);
            alert('Referensi berhasil disalin!');
        }
    </script>
@endsection