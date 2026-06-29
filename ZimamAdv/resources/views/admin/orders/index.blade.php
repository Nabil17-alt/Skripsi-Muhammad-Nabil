@extends('layouts.admin')

@section('page_title', 'Pesanan')

@section('content')
    <div class="mb-6">
        <div class="text-sm text-slate-500">Kelola dan pantau semua pesanan dari pelanggan serta status produksinya.</div>
    </div>

    @if($orders->count() > 0)
        <div class="table-wrapper">
            <table class="table-default">
                <thead>
                    <tr>
                        <th>No. Pesanan</th>
                        <th>Customer</th>
                        <th class="text-center">Status Produksi</th>
                        <th class="text-center">Status Pembayaran</th>
                        <th class="text-right">Total</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                        <tr>
                            <td>
                                <p class="font-semibold text-slate-900">#{{ $order->order_number }}</p>
                                <p class="text-xs text-slate-500 mt-0.5">{{ $order->created_at ? \Carbon\Carbon::parse($order->created_at)->format('d M Y') : '-' }}</p>
                            </td>
                            <td>
                                <p class="font-medium text-slate-900">{{ $order->user->name ?? '-' }}</p>
                                <p class="text-xs text-slate-500">{{ $order->user->email ?? '-' }}</p>
                            </td>
                            <td class="text-center">
                                @php
                                    $prodStatusBadges = [
                                        'pending' => ['bg-slate-100', 'text-slate-700', 'Menunggu'],
                                        'proses' => ['bg-blue-50', 'text-blue-700', 'Diproses'],
                                        'selesai' => ['bg-emerald-50', 'text-emerald-700', 'Selesai'],
                                        'dibatalkan' => ['bg-rose-50', 'text-rose-700', 'Batal'],
                                    ];
                                    $prodStatus = strtolower($order->production_status ?? '');
                                    $prodBadge = $prodStatusBadges[$prodStatus] ?? ['bg-slate-50', 'text-slate-700', $order->production_status ?? '-'];

                                    if (str_contains($prodStatus, 'siap 50%')) {
                                        $prodBadge = ['bg-indigo-50', 'text-indigo-700', $order->production_status];
                                    } elseif (str_contains($prodStatus, 'cetak') || str_contains($prodStatus, 'desain')) {
                                        $prodBadge = ['bg-blue-50', 'text-blue-700', $order->production_status];
                                    }
                                @endphp
                                @if($order->production_status)
                                    <span class="badge-status {{ $prodBadge[0] }} {{ $prodBadge[1] }}">{{ $prodBadge[2] }}</span>
                                @else
                                    <span class="text-sm text-slate-500">-</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @php
                                    $payStatusBadges = [
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
                                    $payBadge = $payStatusBadges[$order->payment_status] ?? ['bg-slate-50', 'text-slate-700', ucfirst(str_replace('_', ' ', $order->payment_status))];
                                @endphp
                                <span class="badge-status {{ $payBadge[0] }} {{ $payBadge[1] }}">{{ $payBadge[2] }}</span>
                            </td>
                            <td class="text-right font-bold text-emerald-600">
                                Rp {{ number_format($order->grand_total, 0, ',', '.') }}
                            </td>
                            <td class="text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('admin.orders.show', $order) }}" class="btn-secondary text-xs">Detail</a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($orders->hasPages())
            <div class="mt-6">
                {{ $orders->links() }}
            </div>
        @endif
    @else
        <div class="card card-body text-center py-12">
            <svg class="mx-auto h-12 w-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
            </svg>
            <p class="text-slate-600 mt-4">Belum ada data pesanan</p>
        </div>
    @endif
@endsection