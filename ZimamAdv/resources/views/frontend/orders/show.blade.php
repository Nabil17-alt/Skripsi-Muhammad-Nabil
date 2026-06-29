@extends('layouts.frontend')

@section('title', 'Lacak Pesanan - Zimam Advertising')

@section('content')
        <div class="max-w-4xl mx-auto">
            <div class="flex items-center justify-between mb-8">
                <div class="flex items-center gap-3">
                    <a href="{{ route('orders.track-form') }}" class="text-slate-400 hover:text-blue-600 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    </a>
                    <h2 class="text-2xl font-bold text-slate-800">Detail Pesanan</h2>
                </div>
                <div class="flex items-center gap-3">
                    <div class="px-4 py-1.5 bg-blue-50 text-blue-700 font-bold rounded-full text-sm border border-blue-100">
                        {{ $order->order_number }}
                    </div>
                    <a href="{{ route('chat.index') }}" class="btn-primary py-1.5 px-4 text-sm flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                        Chat Admin
                    </a>
                </div>
            </div>

            <!-- Stepper Lacak Pesanan -->
            <div class="card-premium p-8 mb-8 overflow-hidden relative">
                <h3 class="font-bold text-slate-800 mb-8 flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Status Pengiriman & Produksi
                </h3>

                @php

                    $isDelivery = $order->delivery_method === 'antar';

                    $deliveryText = $isDelivery
                        ? 'Sedang Dikirim'
                        : 'Siap Diambil';


                    $steps = [
                        'Menunggu Pembayaran' => 1,
                        'Pembayaran Dikonfirmasi' => 2,
                        'Sedang Produksi' => 3,
                        $deliveryText => 4,
                        'Selesai' => 5
                    ];


                    $currentStepIndex = 1;

                    $paymentStatus = strtolower($order->payment_status ?? '');
                    $productionStatus = strtolower($order->production_status ?? '');


                    if (
                        in_array($paymentStatus, [
                            'pending',
                            'belum_dibayar'
                        ])
                    ) {

                        $currentStepIndex = 1;

                    } elseif (
                        in_array($paymentStatus, [
                            'lunas',
                            'sebagian_dibayar'
                        ])
                    ) {

                        $currentStepIndex = 2;


                        if (
                            str_contains($productionStatus, 'produksi') ||
                            str_contains($productionStatus, 'diproses') ||
                            str_contains($productionStatus, 'desain') ||
                            str_contains($productionStatus, 'cetak') ||
                            str_contains($productionStatus, 'siap 50%')
                        ) {

                            $currentStepIndex = 3;

                        } elseif (
                            str_contains($productionStatus, 'kirim') ||
                            str_contains($productionStatus, 'antar') ||
                            str_contains($productionStatus, 'ambil') ||
                            str_contains($productionStatus, 'jemput')
                        ) {

                            $currentStepIndex = 4;

                        } elseif (
                            str_contains($productionStatus, 'selesai')
                        ) {

                            $currentStepIndex = 5;

                        }

                    }

                @endphp

                <div class="relative">
                    <!-- Progress Bar Line -->
                    <div class="hidden md:block absolute top-5 left-8 right-8 h-1 bg-slate-100 rounded-full">
                        <div class="h-full bg-blue-500 rounded-full transition-all duration-1000 ease-in-out" style="width: {{ (($currentStepIndex - 1) / 4) * 100 }}%"></div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-5 gap-6 relative z-10">
                        @foreach($steps as $name => $index)
                            @php
    $isCompleted = $index < $currentStepIndex;
    $isCurrent = $index === $currentStepIndex;

    $bgColor = $isCompleted ? 'bg-blue-500 text-white' : ($isCurrent ? 'bg-white border-2 border-blue-500 text-blue-600 shadow-md shadow-blue-200' : 'bg-slate-100 text-slate-400');
    $icon = $isCompleted ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>' : ($isCurrent ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>' : '<circle cx="12" cy="12" r="3"></circle>');
                            @endphp

                            <div class="flex md:flex-col items-center gap-4 md:gap-3">
                                <div class="flex-shrink-0 w-10 h-10 rounded-full flex items-center justify-center font-bold {{ $bgColor }} transition-colors duration-300 relative z-20">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $icon !!}</svg>
                                </div>
                                <div class="md:text-center">
                                    <p class="text-sm font-bold {{ $isCompleted || $isCurrent ? 'text-slate-800' : 'text-slate-400' }} leading-tight mb-1">{{ $name }}</p>
                                    @if($isCurrent)
                                        <p class="text-[10px] text-blue-600 font-semibold uppercase tracking-wide">Status Saat Ini</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            @if(strcasecmp($order->production_status, 'Siap 50% (Ambil di Toko)') == 0)
                <div class="mb-8 p-5 bg-gradient-to-r from-blue-50 to-indigo-100 border border-blue-200 rounded-2xl flex items-start gap-4 shadow-sm animate-pulse">
                    <div class="w-12 h-12 bg-blue-600 text-white rounded-xl flex items-center justify-center shrink-0 shadow-md shadow-blue-500/20">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-slate-800 text-base mb-1">Pesanan Siap 50% & Dapat Diambil di Toko!</h4>
                        <p class="text-xs text-slate-600 leading-relaxed">Sebagian dari pesanan Anda (50%) telah selesai diproduksi. Anda dapat mengambil bagian yang sudah siap ini langsung di toko **Zimam Advertising** (Jl. Pemda, Pangkalan Kerinci), meskipun metode pengiriman awal Anda adalah Diantar.</p>
                    </div>
                </div>
            @endif

            <!-- Dua Kolom Informasi -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                <!-- Informasi Pesanan -->
                <div class="card-premium p-6">
                    <h3 class="font-bold text-slate-800 mb-4 pb-3 border-b border-slate-100">Informasi Pembayaran</h3>
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between items-center py-1">
                            <span class="text-slate-500">Status Pembayaran</span>
                            @if($order->payment_status === 'lunas')
                                <span class="px-2 py-1 bg-green-100 text-green-700 rounded text-xs font-bold uppercase">Lunas</span>
                            @elseif($order->payment_status === 'sebagian_dibayar')
                                <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded text-xs font-bold uppercase">Dicicil</span>
                            @else
                                <span class="px-2 py-1 bg-amber-100 text-amber-700 rounded text-xs font-bold uppercase">{{ str_replace('_', ' ', $order->payment_status) }}</span>
                            @endif
                        </div>
                        <div class="flex justify-between items-center py-1">
                            <span class="text-slate-500">Status Produksi</span>

                            <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded text-xs font-bold uppercase">
                                {{ $order->production_status }}
                            </span>
                        </div>
                        <div class="flex justify-between items-center py-1">
                            <span class="text-slate-500">Subtotal Belanja</span>
                            <span class="font-medium text-slate-800">Rp {{ number_format($order->subtotal_amount, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center py-1">
                            <span class="text-slate-500">Ongkos Kirim</span>
                            <span class="font-medium text-slate-800">Rp {{ number_format($order->shipping_fee, 0, ',', '.') }}</span>
                        </div>
                        <div class="border-t border-slate-100 pt-3 flex justify-between items-center">
                            <span class="font-bold text-slate-800">Total Transaksi</span>
                            <span class="text-lg font-extrabold text-blue-600">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <div class="mt-6">
                        <a href="{{ route('payments.show', $order->order_number) }}" class="w-full btn-secondary text-center block">Lihat Detail Pembayaran / Tagihan</a>
                    </div>
                </div>

                <!-- Informasi Pengiriman -->
                <div class="card-premium p-6">
                    <h3 class="font-bold text-slate-800 mb-4 pb-3 border-b border-slate-100">Informasi Pengiriman</h3>
                    @if($order->shippingAddress)
                        <div class="space-y-3 text-sm">
                            <div>
                                <span class="text-xs text-slate-400 block mb-1 uppercase tracking-wider font-semibold">Penerima</span>
                                <p class="font-medium text-slate-800">{{ $order->shippingAddress->recipient_name }}</p>
                                <p class="text-slate-600">{{ $order->shippingAddress->phone }}</p>
                            </div>
                            <div>
                                <span class="text-xs text-slate-400 block mb-1 uppercase tracking-wider font-semibold">Alamat Tujuan</span>
                                <p class="text-slate-700">{{ $order->shippingAddress->full_address }}</p>
                            </div>
                            <div>
                                <span class="text-xs text-slate-400 block mb-1 uppercase tracking-wider font-semibold">Metode Pengiriman</span>
                                <div class="flex items-center gap-2 mt-1">
                                    @if($isDelivery)
                                        <span class="px-2 py-1 bg-green-100 text-green-700 rounded text-xs font-bold uppercase flex items-center gap-1">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
                                            Diantar (Radius < 1 KM)
                                        </span>
                                    @else
                                        <span class="px-2 py-1 bg-amber-100 text-amber-700 rounded text-xs font-bold uppercase flex items-center gap-1">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                            Ambil di Toko
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @else
                        <p class="text-sm text-slate-500 italic">Informasi alamat tidak tersedia.</p>
                    @endif
                </div>
            </div>

            <!-- Rincian Item -->
            <div class="card-premium p-6">
                <h3 class="font-bold text-slate-800 mb-4">Rincian Item</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-slate-200 bg-slate-50/50">
                                <th class="p-3 text-left font-semibold text-slate-600 rounded-tl-lg">Produk</th>
                                <th class="p-3 text-center font-semibold text-slate-600">Jumlah</th>
                                <th class="p-3 text-right font-semibold text-slate-600">Harga</th>
                                <th class="p-3 text-right font-semibold text-slate-600">Jasa Desain</th>
                                <th class="p-3 text-right font-semibold text-slate-600 rounded-tr-lg">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($order->items as $item)
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="p-3">
                                        <div class="font-medium text-slate-800">{{ $item->product->name ?? '-' }}</div>
                                        @if($item->notes)
                                            <div class="text-xs text-slate-500 mt-1 flex items-start gap-1">
                                                <svg class="w-3 h-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                                {{ $item->notes }}
                                            </div>
                                        @endif
                                    </td>
                                    <td class="p-3 text-center text-slate-700">{{ $item->quantity }}</td>
                                    <td class="p-3 text-right text-slate-700">Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                                    <td class="p-3 text-right text-slate-700">Rp {{ number_format($item->design_service_fee, 0, ',', '.') }}</td>
                                    <td class="p-3 text-right font-medium text-slate-800">Rp {{ number_format(($item->unit_price * $item->quantity) + $item->design_service_fee, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
@endsection