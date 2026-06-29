@extends('layouts.admin')

@section('page_title', 'Detail Pesanan')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.orders.index') }}" class="h-10 w-10 bg-white border border-slate-200 rounded-xl flex items-center justify-center text-slate-400 hover:text-blue-600 transition-colors shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <div>
                <h2 class="text-2xl font-black text-slate-800">Detail Pesanan #{{ $order->order_number }}</h2>
                <p class="text-xs text-slate-500 font-medium uppercase tracking-widest">Dibuat pada: {{ $order->created_at->format('d M Y, H:i') }}</p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <span class="px-3 py-1.5 rounded-lg text-xs font-bold uppercase tracking-wider {{ $order->payment_status === 'lunas' ? 'bg-emerald-50 text-emerald-600 border border-emerald-100' : 'bg-amber-50 text-amber-600 border border-amber-100' }}">
                {{ $order->payment_status }}
            </span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Order Information -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
                    <h3 class="font-bold text-slate-800 flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                        Item Pesanan
                    </h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead>
                            <tr class="bg-slate-50/50 text-slate-400 text-[10px] font-black uppercase tracking-widest">
                                <th class="px-6 py-4">Produk</th>
                                <th class="px-6 py-4 text-center">Qty</th>
                                <th class="px-6 py-4 text-right">Harga</th>
                                <th class="px-6 py-4 text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($order->items as $item)
                                <tr>
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-slate-800">{{ $item->product->name ?? '-' }}</div>
                                        @if($item->notes)
                                            <div class="text-[11px] text-slate-500 mt-1 italic">"{{ $item->notes }}"</div>
                                        @endif
                                        @if($item->design_file_path)
                                            <div class="mt-2">
                                                <a href="{{ asset('storage/' . $item->design_file_path) }}" target="_blank" class="text-[10px] font-bold text-emerald-600 hover:underline flex items-center gap-1">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                                    Lihat File Desain
                                                </a>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center font-bold text-slate-600">{{ $item->quantity }}</td>
                                    <td class="px-6 py-4 text-right text-slate-500">Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                                    <td class="px-6 py-4 text-right font-bold text-slate-800">Rp {{ number_format(($item->unit_price * $item->quantity) + $item->design_service_fee, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-slate-50/50">
                            <tr>
                                <td colspan="3" class="px-6 py-4 text-right font-bold text-slate-500 uppercase tracking-widest text-[10px]">Grand Total</td>
                                <td class="px-6 py-4 text-right font-black text-blue-600 text-lg">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <!-- Shipping & Map Section -->
            @if($order->shippingAddress)
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
                        <h3 class="font-bold text-slate-800 flex items-center gap-2">
                            <svg class="w-5 h-5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            Informasi Pengiriman
                        </h3>
                        <div class="flex items-center gap-2">
                            @if($order->delivery_method === 'antar')
                                <span class="text-[10px] font-bold px-2 py-1 bg-emerald-50 text-emerald-600 rounded-full border border-emerald-200">DIANTAR</span>
                            @else
                                <span class="text-[10px] font-bold px-2 py-1 bg-slate-100 text-slate-500 rounded-full border border-slate-200">AMBIL DI TOKO</span>
                            @endif
                            
                            @if($order->shipping_distance_km !== null)
                                <span class="text-[10px] font-bold px-2 py-1 bg-blue-50 text-blue-600 rounded-full border border-blue-100 italic">
                                    Jarak: {{ $order->shipping_distance_km }} KM
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="p-6">
                        @if($order->delivery_method === 'antar' && $order->shipping_distance_km <= 1)
                            {{-- TAMPILKAN MAP JIKA DIANTAR & JARAK < 1KM --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-4">
                                    <div class="p-4 bg-slate-50 rounded-xl border border-slate-100">
                                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Alamat Penerima</p>
                                        <p class="text-sm font-bold text-slate-700 leading-relaxed">{{ $order->shippingAddress->full_address }}</p>
                                    </div>
                                    <div class="p-4 bg-slate-50 rounded-xl border border-slate-100">
                                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Penerima & Kontak</p>
                                        <p class="text-sm font-bold text-slate-700">{{ $order->shippingAddress->recipient_name }}</p>
                                        <p class="text-xs text-blue-600 font-bold mt-1">{{ $order->shippingAddress->phone }}</p>
                                    </div>
                                    <a href="https://www.google.com/maps?q={{ $order->shippingAddress->latitude }},{{ $order->shippingAddress->longitude }}" target="_blank" class="w-full py-3 bg-emerald-500 text-white rounded-xl font-bold text-sm flex items-center justify-center gap-2 shadow-lg shadow-emerald-500/20 hover:bg-emerald-600 transition-all active:scale-95">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path></svg>
                                        Navigasi Google Maps
                                    </a>
                                </div>
                                <div class="h-64 md:h-full min-h-[250px] rounded-2xl overflow-hidden border border-slate-200 shadow-inner relative z-0" id="admin-order-map">
                                    {{-- Map Container --}}
                                </div>
                            </div>
                        @else
                            {{-- TAMPILKAN INFO BIASA JIKA AMBIL DI TOKO --}}
                            <div class="flex flex-col md:flex-row gap-6">
                                <div class="flex-1 space-y-4">
                                    <div class="p-4 bg-amber-50 border border-amber-100 rounded-xl flex items-center gap-3">
                                        <div class="w-10 h-10 bg-amber-100 rounded-full flex items-center justify-center text-amber-600 shrink-0">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-amber-800">Pelanggan Akan Mengambil Sendiri</p>
                                            <p class="text-xs text-amber-600">Pesanan ini berada di luar radius pengantaran atau pelanggan memilih ambil di toko.</p>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div class="p-4 bg-slate-50 rounded-xl border border-slate-100">
                                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Identitas Pemesan</p>
                                            <p class="text-sm font-bold text-slate-700">{{ $order->shippingAddress->recipient_name }}</p>
                                            <p class="text-xs text-blue-600 font-bold mt-1">{{ $order->shippingAddress->phone }}</p>
                                        </div>
                                        <div class="p-4 bg-slate-50 rounded-xl border border-slate-100">
                                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Status Lokasi</p>
                                            <p class="text-sm font-bold text-slate-700">Jarak: {{ $order->shipping_distance_km ?? '-' }} KM</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar Actions -->
        <div class="space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                <h4 class="font-bold text-slate-800 mb-4 border-b border-slate-100 pb-3 uppercase tracking-widest text-[10px]">Update Status Produksi</h4>
                <form action="{{ route('admin.orders.update-status', $order) }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        @php
                            $totalQty = $order->items->sum('quantity');
                            $statuses = ['Menunggu Pembayaran', 'Menunggu Konfirmasi', 'Diproses', 'Sedang Didesain', 'Sedang Dicetak'];
                            if ($totalQty > 1) {
                                $statuses[] = 'Siap 50% (Ambil di Toko)';
                            }
                            $statuses = array_merge($statuses, ['Siap Diambil', 'Sedang Dikirim', 'Selesai']);
                        @endphp
                        <select name="production_status" class="w-full border-slate-200 rounded-xl focus:ring-blue-500 focus:border-blue-500 text-sm font-bold text-slate-700 bg-slate-50">
                            @foreach($statuses as $st)
                                <option value="{{ $st }}" {{ strcasecmp($order->production_status, $st) == 0 ? 'selected' : '' }}>{{ $st }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="w-full btn-primary py-3 font-bold text-sm shadow-xl shadow-blue-500/20">
                        Update Status
                    </button>
                </form>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                <h4 class="font-bold text-slate-800 mb-4 border-b border-slate-100 pb-3 uppercase tracking-widest text-[10px]">Data Customer</h4>
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-slate-100 rounded-full flex items-center justify-center text-slate-400 font-black text-lg">
                        {{ substr($order->user->name ?? '?', 0, 1) }}
                    </div>
                    <div>
                        <p class="text-sm font-bold text-slate-800">{{ $order->user->name ?? '-' }}</p>
                        <p class="text-xs text-slate-500">{{ $order->user->email ?? '-' }}</p>
                        <p class="text-xs text-slate-500">{{ $order->user->phone ?? '-' }}</p>
                    </div>
                </div>
                <div class="mt-6">
                    <a href="{{ route('admin.chats.index') }}?user_id={{ $order->user_id }}" class="w-full py-3 bg-slate-50 border border-slate-200 text-slate-700 rounded-xl font-bold text-xs flex items-center justify-center gap-2 hover:bg-slate-100 transition-colors">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                        Hubungi via Chat
                    </a>
                </div>
            </div>
        </div>
    </div>

    @if($order->shippingAddress)
        @push('scripts')
            <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
            <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    const lat = {{ $order->shippingAddress->latitude }};
                    const lng = {{ $order->shippingAddress->longitude }};
                    
                    const map = L.map('admin-order-map').setView([lat, lng], 16);
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        maxZoom: 19,
                    }).addTo(map);

                    const marker = L.marker([lat, lng], {
                        icon: L.icon({
                            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
                            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                            iconSize: [25, 41], iconAnchor: [12, 41], popupAnchor: [1, -34], shadowSize: [41, 41]
                        })
                    }).addTo(map);
                    
                    marker.bindPopup("<b>Lokasi Pengiriman</b><br>{{ $order->shippingAddress->recipient_name }}").openPopup();
                });
            </script>
        @endpush
    @endif
@endsection