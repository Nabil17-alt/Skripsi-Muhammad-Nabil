@extends('layouts.admin')

@section('page_title', 'Verifikasi Pembayaran')

@section('content')
    <div class="mb-6">
        <div class="text-sm text-slate-500">Kelola dan verifikasi semua pembayaran pesanan dari pelanggan.</div>
    </div>

    @if($payments->count() > 0)
        <div class="table-wrapper">
            <table class="table-default">
                <thead>
                    <tr>
                        <th>No. Pesanan</th>
                        <th>Customer</th>
                        <th>Metode</th>
                        <th class="text-right">Jumlah</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($payments as $payment)
                        <tr>
                            <td>
                                <p class="font-semibold text-slate-900">#{{ $payment->order->order_number ?? '-' }}</p>
                                <p class="text-xs text-slate-500 mt-0.5">{{ $payment->order->created_at?->format('d M Y') }}</p>
                            </td>
                            <td>
                                <p class="font-medium text-slate-900">{{ $payment->order->user->name ?? '-' }}</p>
                                <p class="text-xs text-slate-500">{{ $payment->order->user->email ?? '-' }}</p>
                            </td>
                            <td>
                                <div class="flex items-center gap-2">
                                    @if($payment->method)
                                        @php
                                            $methodType = strtolower($payment->method->name);
                                            $icons = [
                                                'transfer' => '🏦',
                                                'qris' => '📱',
                                                'ewallet' => '💳',
                                                'cicilan' => '📊'
                                            ];
                                            $icon = collect($icons)->first(fn($v, $k) => str_contains($methodType, $k)) ?? '💰';
                                        @endphp
                                        <span class="text-lg">{{ $icon }}</span>
                                        <span class="text-sm font-medium">{{ $payment->method->name }}</span>
                                    @else
                                        <span class="text-sm text-slate-500">-</span>
                                    @endif
                                </div>
                            </td>
                            <td class="text-right font-bold text-emerald-600">Rp {{ number_format($payment->amount, 0, ',', '.') }}
                            </td>
                            <td class="text-center">
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
                                        'menunggu_verifikasi' => ['bg-blue-50', 'text-blue-700', 'Perlu Verifikasi'],
                                    ];
                                    $badge = $statusBadges[$payment->status] ?? ['bg-slate-50', 'text-slate-700', ucfirst(str_replace('_', ' ', $payment->status))];
                                @endphp
                                <span class="badge-status {{ $badge[0] }} {{ $badge[1] }}">{{ $badge[2] }}</span>
                            </td>
                            <td class="text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('admin.payments.show', $payment) }}" class="btn-secondary text-xs">Detail</a>
                                    @if($payment->status !== 'lunas')
                                        <form action="{{ route('admin.payments.verify', $payment) }}" method="POST" class="inline">
                                            @csrf
                                            <button class="text-emerald-600 hover:text-emerald-700 text-xs font-medium"
                                                onclick="return confirm('Konfirmasi pembayaran ini?')">Verifikasi</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($payments->hasPages())
            <div class="mt-6">
                {{ $payments->links() }}
            </div>
        @endif
    @else
        <div class="card card-body text-center py-12">
            <svg class="mx-auto h-12 w-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
            </svg>
            <p class="text-slate-600 mt-4">Belum ada data pembayaran</p>
        </div>
    @endif
@endsection