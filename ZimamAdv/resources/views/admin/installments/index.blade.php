@extends('layouts.admin')

@section('page_title', 'Data Cicilan')

@section('content')
    <div class="mb-6">
        <div class="text-sm text-slate-500">Monitoring semua data cicilan pembayaran dari pelanggan.</div>
    </div>

    @if($installments->count() > 0)
        <div class="table-wrapper">
            <table class="table-default">
                <thead>
                    <tr>
                        <th>No. Pesanan</th>
                        <th>Customer</th>
                        <th class="text-center">Cicilan Ke</th>
                        <th class="text-right">Jumlah</th>
                        <th class="text-center">Jatuh Tempo</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($installments as $installment)
                        <tr>
                            <td>
                                <p class="font-semibold text-slate-900">#{{ $installment->payment->order->order_number ?? '-' }}</p>
                                <p class="text-xs text-slate-500 mt-0.5">{{ $installment->payment->created_at?->format('d M Y') }}</p>
                            </td>
                            <td>
                                <p class="font-medium text-slate-900">{{ $installment->payment->order->user->name ?? '-' }}</p>
                                <p class="text-xs text-slate-500">{{ $installment->payment->order->user->email ?? '-' }}</p>
                            </td>
                            <td class="text-center font-semibold text-slate-700">
                                {{ $installment->sequence }}
                            </td>
                            <td class="text-right font-bold text-emerald-600">
                                Rp {{ number_format($installment->amount, 0, ',', '.') }}
                            </td>
                            <td class="text-center text-sm text-slate-600">
                                {{ $installment->due_date ? \Carbon\Carbon::parse($installment->due_date)->format('d M Y') : '-' }}
                            </td>
                            <td class="text-center">
                                @php
                                    $statusBadges = [
                                        'pending' => ['bg-amber-50', 'text-amber-700', 'Menunggu'],
                                        'menunggu_konfirmasi' => ['bg-blue-50', 'text-blue-700', 'Perlu Verifikasi'],
                                        'paid' => ['bg-emerald-50', 'text-emerald-700', 'Lunas'],
                                        'overdue' => ['bg-rose-50', 'text-rose-700', 'Terlambat'],
                                    ];
                                    $badge = $statusBadges[$installment->status] ?? ['bg-slate-50', 'text-slate-700', ucfirst(str_replace('_', ' ', $installment->status))];
                                @endphp
                                <span class="badge-status {{ $badge[0] }} {{ $badge[1] }}">{{ $badge[2] }}</span>
                            </td>
                            <td class="text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('admin.payments.show', $installment->payment_id) }}" class="btn-secondary text-xs">Detail Pembayaran</a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($installments->hasPages())
            <div class="mt-6">
                {{ $installments->links() }}
            </div>
        @endif
    @else
        <div class="card card-body text-center py-12">
            <svg class="mx-auto h-12 w-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
            </svg>
            <p class="text-slate-600 mt-4">Belum ada data cicilan</p>
        </div>
    @endif
@endsection
