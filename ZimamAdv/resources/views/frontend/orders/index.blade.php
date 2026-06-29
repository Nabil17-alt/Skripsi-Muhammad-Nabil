@extends('layouts.frontend')

@section('title', 'Pesanan Saya - Zimam Advertising')

@section('content')
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Pesanan Saya</h1>
            <p class="text-slate-500 text-sm">Pantau status produksi dan pembayaran pesanan Anda.</p>
        </div>
        <a href="{{ route('chat.index') }}" class="btn-secondary flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
            Tanya Admin
        </a>
    </div>

    <div class="card-premium overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-slate-50 border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4 font-bold text-slate-600 uppercase tracking-wider text-[10px]">No. Pesanan</th>
                        <th class="px-6 py-4 font-bold text-slate-600 uppercase tracking-wider text-[10px]">Tanggal</th>
                        <th class="px-6 py-4 font-bold text-slate-600 uppercase tracking-wider text-[10px]">Status Produksi</th>
                        <th class="px-6 py-4 font-bold text-slate-600 uppercase tracking-wider text-[10px]">Pembayaran</th>
                        <th class="px-6 py-4 font-bold text-slate-600 uppercase tracking-wider text-[10px] text-right">Total</th>
                        <th class="px-6 py-4 font-bold text-slate-600 uppercase tracking-wider text-[10px] text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse($orders as $order)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4">
                                <span class="font-black text-slate-800">#{{ $order->order_number }}</span>
                            </td>
                            <td class="px-6 py-4 text-slate-500">
                                {{ $order->created_at?->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $prodStatus = strtolower($order->production_status ?? '');
                                    $prodColor = 'bg-slate-100 text-slate-600';
                                    if(str_contains($prodStatus, 'selesai')) $prodColor = 'bg-emerald-100 text-emerald-700';
                                    elseif(str_contains($prodStatus, 'siap 50%')) $prodColor = 'bg-indigo-100 text-indigo-700';
                                    elseif(str_contains($prodStatus, 'proses') || str_contains($prodStatus, 'cetak') || str_contains($prodStatus, 'desain')) $prodColor = 'bg-blue-100 text-blue-700';
                                    elseif(str_contains($prodStatus, 'konfirmasi')) $prodColor = 'bg-amber-100 text-amber-700';
                                @endphp
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase {{ $prodColor }}">
                                    {{ $order->production_status }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @if($order->payment_status === 'lunas')
                                    <span class="px-2.5 py-1 rounded-full bg-emerald-100 text-emerald-700 text-[10px] font-bold uppercase">Lunas</span>
                                @elseif($order->payment_status === 'sebagian_dibayar' || $order->payment_status === 'sudah_dibayar')
                                    <span class="px-2.5 py-1 rounded-full bg-blue-100 text-blue-700 text-[10px] font-bold uppercase">Dicicil</span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full bg-amber-100 text-amber-700 text-[10px] font-bold uppercase">Belum Bayar</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right font-bold text-slate-800">
                                Rp {{ number_format($order->grand_total, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('orders.show', $order->order_number) }}" class="p-2 text-slate-400 hover:text-blue-600 transition-colors" title="Detail Pesanan">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    </a>
                                    <a href="{{ route('payments.show', $order->order_number) }}" class="p-2 text-slate-400 hover:text-emerald-600 transition-colors" title="Pembayaran">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-slate-500 italic">
                                Belum ada riwayat pesanan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($orders->hasPages())
        <div class="mt-6">
            {{ $orders->links() }}
        </div>
    @endif
@endsection