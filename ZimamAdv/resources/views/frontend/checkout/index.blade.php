@extends('layouts.frontend')

@section('title', 'Checkout - Zimam Advertising')

@section('content')
<div class="max-w-5xl mx-auto">
    <div class="flex items-center gap-3 mb-8">
        <a href="{{ route('cart.index') }}" class="text-slate-400 hover:text-blue-600 transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <h2 class="text-2xl font-bold text-slate-800">Selesaikan Pesanan</h2>
    </div>

    @if(empty($cart))
        <div class="bg-white rounded-2xl p-8 text-center border border-slate-100 shadow-sm">
            <svg class="w-16 h-16 text-slate-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            <p class="text-lg font-medium text-slate-600 mb-4">Keranjang belanja Anda masih kosong.</p>
            <a href="{{ route('products.index') }}" class="btn-primary inline-flex">Belanja Sekarang</a>
        </div>
    @else
        <form action="{{ route('checkout.process') }}" method="POST" id="checkout-form" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            @csrf
            
            <div class="lg:col-span-2 space-y-6">
                <!-- Informasi Kontak -->
                <div class="card-premium p-6">
                    <h3 class="font-bold text-slate-800 mb-4 flex items-center gap-2 border-b border-slate-100 pb-3">
                        <span class="bg-blue-100 text-blue-600 w-6 h-6 rounded-full flex items-center justify-center text-xs">1</span>
                        Informasi Kontak
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Nama Penerima <span class="text-red-500">*</span></label>
                            <input type="text" name="customer_name" class="w-full border-slate-200 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-sm py-2 px-3 border" value="{{ auth()->user()->name ?? '' }}" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Nomor HP / WhatsApp <span class="text-red-500">*</span></label>
                            <input type="tel" name="customer_phone" class="w-full border-slate-200 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-sm py-2 px-3 border" required placeholder="08xxxxxxxxxx">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-slate-700 mb-1">Alamat Lengkap <span class="text-red-500">*</span></label>
                            <textarea name="full_address" rows="3" class="w-full border-slate-200 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-sm py-2 px-3 border" required placeholder="Nama Jalan, RT/RW, Kecamatan, Kota"></textarea>
                        </div>
                    </div>
                </div>

                <!-- Pengiriman & Peta -->
                <div class="card-premium p-6">
                    <h3 class="font-bold text-slate-800 mb-2 flex items-center gap-2">
                        <span class="bg-blue-100 text-blue-600 w-6 h-6 rounded-full flex items-center justify-center text-xs">2</span>
                        Lokasi Pengiriman
                    </h3>
                    <p class="text-xs text-slate-500 mb-4 pb-3 border-b border-slate-100">
                        Aktifkan GPS atau pilih lokasi pada peta di bawah ini. <br>
                        <strong class="text-green-600">Radius < 1 KM:</strong> Gratis ongkir (Diantar). <strong class="text-amber-600">Radius > 1 KM:</strong> Ambil di toko.
                    </p>
                    
                    <input type="hidden" name="latitude" id="latitude">
                    <input type="hidden" name="longitude" id="longitude">

                    <div class="rounded-xl overflow-hidden border border-slate-200 relative">
                        <div id="map" class="w-full h-64 z-10"></div>
                        <div class="absolute top-2 right-2 z-20">
                            <button type="button" onclick="locateUser()" class="bg-white p-2 rounded-lg shadow-md text-slate-700 hover:text-blue-600 transition-colors" title="Cari Lokasi Saya">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            </button>
                        </div>
                    </div>
                    
                    <div id="shipping-alert" class="mt-4 p-3 rounded-lg border hidden flex items-start gap-3">
                        <div id="shipping-icon" class="mt-0.5"></div>
                        <div>
                            <h4 id="shipping-title" class="font-semibold text-sm"></h4>
                            <p id="shipping-info" class="text-xs mt-1"></p>
                        </div>
                    </div>
                </div>

                <!-- Metode Pembayaran -->
                <div class="card-premium p-6">
                    <h3 class="font-bold text-slate-800 mb-4 flex items-center gap-2 border-b border-slate-100 pb-3">
                        <span class="bg-blue-100 text-blue-600 w-6 h-6 rounded-full flex items-center justify-center text-xs">3</span>
                        Metode Pembayaran
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-4">
                        @foreach($paymentMethods as $method)
                            <label class="relative flex cursor-pointer rounded-lg border border-slate-200 bg-white p-4 shadow-sm hover:border-blue-400 focus:outline-none payment-label transition-all">
                                <input type="radio" name="payment_method_id" value="{{ $method->id }}" data-type="{{ $method->type }}" class="sr-only peer payment-radio main-payment-radio" id="method-{{ $method->type }}" required>
                                <span class="peer-checked:border-blue-500 peer-checked:ring-1 peer-checked:ring-blue-500 absolute inset-0 rounded-lg border-2 border-transparent pointer-events-none"></span>
                                <div class="flex flex-1">
                                    <div class="flex flex-col">
                                        <span class="block text-sm font-bold text-slate-800">{{ $method->name }}</span>
                                        @if($method->type === 'qris')
                                            <span class="mt-1 flex items-center text-xs text-slate-500">Bayar instan dengan QRIS</span>
                                        @elseif($method->type === 'ewallet')
                                            <span class="mt-1 flex items-center text-xs text-slate-500">DANA, OVO, ShopeePay</span>
                                        @elseif($method->type === 'installment')
                                            <span class="mt-1 flex items-center text-xs text-blue-600 font-medium">Cicilan tersedia</span>
                                        @else
                                            <span class="mt-1 flex items-center text-xs text-slate-500">Verifikasi manual</span>
                                        @endif
                                    </div>
                                </div>
                                <svg class="h-5 w-5 text-blue-600 opacity-0 peer-checked:opacity-100 transition-opacity" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                            </label>
                        @endforeach
                    </div>

                    <!-- Setup Cicilan -->
                    <div id="installment-section" class="hidden animate-fade-in mt-6 border border-blue-100 bg-blue-50/50 rounded-xl p-4">
                        <div class="flex items-center gap-2 mb-3 text-blue-800">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                            <h4 class="font-bold text-sm">Simulasi Cicilan (Khusus Transaksi > Rp {{ number_format(config('installments.min_amount'), 0, ',', '.') }})</h4>
                        </div>
                        
                        @if($cartTotal <= config('installments.min_amount'))
                            <div class="bg-red-50 text-red-600 text-xs p-3 rounded-lg border border-red-100 flex items-start gap-2">
                                <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                                <span>Maaf, total belanja Anda (Rp {{ number_format($cartTotal, 0, ',', '.') }}) belum memenuhi syarat minimal untuk cicilan (Rp {{ number_format(config('installments.min_amount'), 0, ',', '.') }}). Silakan pilih metode lain.</span>
                            </div>
                        @else
                            <div class="mb-4">
                                <label class="block text-xs font-semibold text-slate-700 mb-1">Tenor Cicilan Sisa Pembayaran (Otomatis)</label>
                                <div class="inline-flex items-center px-4 py-2 border border-blue-200 bg-blue-50 rounded-xl text-sm font-bold text-blue-800 gap-1.5 shadow-sm">
                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    <span>{{ $allowedTenor }} Bulan</span>
                                </div>
                                <input type="hidden" name="installment_tenor" id="installment_tenor" value="{{ $allowedTenor }}">
                            </div>
                            
                            <div class="bg-white rounded-lg border border-blue-200 overflow-hidden text-sm">
                                <div class="bg-blue-600 text-white px-4 py-2 text-xs font-bold uppercase tracking-wider">
                                    Simulasi Angsuran
                                </div>
                                <div class="p-4 space-y-3">
                                    <div class="flex justify-between items-center pb-2 border-b border-slate-100">
                                        <span class="text-slate-600">Total Belanja</span>
                                        <span class="font-bold text-slate-800">Rp {{ number_format($cartTotal, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="flex justify-between items-center pb-2 border-b border-slate-100">
                                        <div>
                                            <span class="font-bold text-slate-800 block">DP (Down Payment) - {{ config('installments.dp_percent') }}%</span>
                                            <span class="text-[10px] text-slate-500 italic">*Wajib dibayar untuk memproses pesanan</span>
                                        </div>
                                        <span class="font-extrabold text-blue-700 text-base" id="dp-amount">Rp {{ number_format($cartTotal * (config('installments.dp_percent') / 100), 0, ',', '.') }}</span>
                                    </div>
                                    <div class="flex justify-between items-center pt-1">
                                        <div>
                                            <span class="font-semibold text-slate-700 block" id="installment-desc">Cicilan Bulanan</span>
                                            <span class="text-[10px] text-slate-500 italic">*Bayar bertahap ke rekening bank kami</span>
                                        </div>
                                        <span class="font-bold text-emerald-600" id="monthly-amount">Rp 0 / bln</span>
                                    </div>
                                </div>
                                <div class="bg-slate-50 px-4 py-3 border-t border-slate-100 text-[11px] text-slate-600 flex items-start gap-2">
                                    <svg class="w-4 h-4 text-blue-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                                    <span>Metode ini memerlukan verifikasi manual oleh Admin. Silakan upload bukti transfer bank setelah melakukan pemesanan.</span>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Ringkasan Pesanan (Sidebar) -->
            <div class="lg:col-span-1">
                <div class="card-premium p-6 sticky top-24">
                    <h3 class="font-bold text-slate-800 mb-4 pb-3 border-b border-slate-100">Ringkasan Pesanan</h3>
                    
                    <div class="space-y-4 mb-6 max-h-60 overflow-y-auto pr-2 custom-scrollbar">
                        @foreach($cart as $id => $item)
                            @php 
                                $itemSubtotal = $item['price'] * $item['quantity'];
                                if (($item['design_option'] ?? 'custom') === 'service') {
                                    $itemSubtotal += ($item['design_service_fee'] ?? 0);
                                }
                            @endphp
                            <div class="flex justify-between gap-2">
                                <div class="flex-1">
                                    <h4 class="text-sm font-semibold text-slate-800 line-clamp-1">{{ $item['name'] }}</h4>
                                    <p class="text-xs text-slate-500">
                                        {{ $item['quantity'] }}x @ Rp {{ number_format($item['price'], 0, ',', '.') }}
                                        @if(($item['design_option'] ?? 'custom') === 'service')
                                            <span class="block text-[10px] text-emerald-600 font-semibold mt-0.5">Jasa Desain (+Rp {{ number_format($item['design_service_fee'] ?? 0, 0, ',', '.') }})</span>
                                        @endif
                                    </p>
                                </div>
                                <span class="text-sm font-semibold text-slate-800">Rp {{ number_format($itemSubtotal, 0, ',', '.') }}</span>
                            </div>
                        @endforeach
                    </div>
                    
                    <div class="border-t border-slate-100 pt-4 space-y-3 mb-6">
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-500">Subtotal</span>
                            <span class="font-medium text-slate-800">Rp {{ number_format($cartTotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-500">Ongkos Kirim</span>
                            <span class="font-medium text-emerald-600" id="summary-shipping">Dihitung dari Peta</span>
                        </div>
                        <div class="border-t border-slate-200 pt-3 flex justify-between items-center">
                            <span class="font-bold text-slate-800">Total Akhir</span>
                            <span class="text-xl font-extrabold text-blue-600">Rp {{ number_format($cartTotal, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <button type="submit" id="checkout-submit" class="w-full btn-primary py-3 text-base flex justify-center items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Bayar Sekarang
                    </button>
                    
                    <div class="mt-4 flex items-center justify-center gap-2 text-xs text-slate-400">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path></svg>
                        Pembayaran Aman Terenkripsi
                    </div>
                </div>
            </div>
        </form>
    @endif
</div>
@endsection

@push('scripts')
    {{-- Leaflet + OpenStreetMap --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        .animate-fade-in { animation: fadeIn 0.3s ease-in-out; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(-5px); } to { opacity: 1; transform: translateY(0); } }
    </style>

    <script>
        const storeLat = 0.407666132432147;
        const storeLng = 101.85619581286325;
        let map, marker;
        
        function initMap() {
            const latInput = document.getElementById('latitude');
            const lngInput = document.getElementById('longitude');
            
            map = L.map('map').setView([storeLat, storeLng], 14);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; OpenStreetMap contributors',
            }).addTo(map);

            const storeMarker = L.marker([storeLat, storeLng], {
                icon: L.icon({
                    iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-blue.png',
                    shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                    iconSize: [25, 41], iconAnchor: [12, 41], popupAnchor: [1, -34], shadowSize: [41, 41]
                })
            }).addTo(map);
            storeMarker.bindPopup('<b>Zimam Advertising</b><br>Toko Utama').openPopup();

            L.circle([storeLat, storeLng], {
                radius: 1000, color: '#10b981', weight: 2, fillColor: '#10b981', fillOpacity: 0.1, dashArray: '5, 5'
            }).addTo(map);

            function updateLocation(lat, lng) {
                latInput.value = lat.toFixed(7);
                lngInput.value = lng.toFixed(7);

                if (marker) {
                    marker.setLatLng([lat, lng]);
                } else {
                    marker = L.marker([lat, lng], {
                        icon: L.icon({
                            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
                            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                            iconSize: [25, 41], iconAnchor: [12, 41], popupAnchor: [1, -34], shadowSize: [41, 41]
                        })
                    }).addTo(map);
                    marker.bindPopup('Lokasi Pengiriman').openPopup();
                }

                map.flyTo([lat, lng], 15, { duration: 1 });
                calculateDistance(lat, lng);
            }

            map.on('click', function (e) { updateLocation(e.latlng.lat, e.latlng.lng); });
            window.locateUser = function() {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(
                        pos => updateLocation(pos.coords.latitude, pos.coords.longitude),
                        err => alert('Gagal mendapatkan lokasi. Silakan pilih di peta.')
                    );
                }
            };
            
            // Try to auto-locate
            locateUser();
        }

        function calculateDistance(lat, lng) {
            const R = 6371;
            const dLat = (lat - storeLat) * Math.PI / 180;
            const dLng = (lng - storeLng) * Math.PI / 180;
            const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) + Math.cos(storeLat * Math.PI / 180) * Math.cos(lat * Math.PI / 180) * Math.sin(dLng / 2) * Math.sin(dLng / 2);
            const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
            const distance = R * c;

            const alertBox = document.getElementById('shipping-alert');
            const title = document.getElementById('shipping-title');
            const info = document.getElementById('shipping-info');
            const icon = document.getElementById('shipping-icon');
            const summary = document.getElementById('summary-shipping');
            
            alertBox.classList.remove('hidden', 'bg-green-50', 'border-green-200', 'text-green-800', 'bg-amber-50', 'border-amber-200', 'text-amber-800');

            if (distance <= 1) {
                alertBox.classList.add('bg-green-50', 'border-green-200', 'text-green-800');
                icon.innerHTML = '<svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>';
                title.textContent = 'Dalam Radius Pengantaran (Gratis Ongkir)';
                info.innerHTML = `Jarak lokasi Anda <b>${distance.toFixed(2)} KM</b> dari toko. Pesanan akan diantar ke alamat Anda.`;
                summary.textContent = 'Gratis (Diantar)';
                summary.className = 'font-medium text-emerald-600';
            } else {
                alertBox.classList.add('bg-amber-50', 'border-amber-200', 'text-amber-800');
                icon.innerHTML = '<svg class="w-5 h-5 text-amber-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>';
                title.textContent = 'Di Luar Radius Pengantaran (> 1 KM)';
                info.innerHTML = `Jarak lokasi Anda <b>${distance.toFixed(2)} KM</b>. Pengiriman kurir toko tidak tersedia, silakan ambil pesanan di toko saat selesai.`;
                summary.textContent = 'Ambil di Toko';
                summary.className = 'font-medium text-amber-600';
            }
        }

        // Payment & E-Wallet / Installment Logic
        const cartTotal = {{ (int) round($cartTotal) }};
        const radios = document.querySelectorAll('.payment-radio');
        const installmentSection = document.getElementById('installment-section');
        const ewalletSection = document.getElementById('ewallet-sub-section');
        const submitBtn = document.getElementById('checkout-submit');
        const tenorSelect = document.getElementById('installment_tenor');
        
        function handlePaymentChange() {
            let isInstallment = false;

            radios.forEach(r => {
                if (r.checked && r.dataset.type === 'installment') isInstallment = true;
            });

            // Handle Installment toggle
            if (isInstallment) {
                installmentSection.classList.remove('hidden');
                if (cartTotal <= {{ config('installments.min_amount') }}) {
                    submitBtn.disabled = true;
                    submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
                } else {
                    submitBtn.disabled = false;
                    submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                    updateSimulation();
                }
            } else {
                installmentSection.classList.add('hidden');
                submitBtn.disabled = false;
                submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            }
        }

        function updateSimulation() {
            if(!tenorSelect) return;
            const tenor = parseInt(tenorSelect.value, 10);
            const remaining = cartTotal * (1 - ({{ config('installments.dp_percent') }} / 100));
            const monthly = Math.ceil(remaining / tenor);
            
            document.getElementById('installment-desc').textContent = `Cicilan (Sisa ${100 - {{ config('installments.dp_percent') }}}% / ${tenor} Bulan)`;
            document.getElementById('monthly-amount').textContent = `Rp ${new Intl.NumberFormat('id-ID').format(monthly)} / bln`;
        }

        // Bind event listeners to all payment radios (both main and sub e-wallets)
        radios.forEach(r => r.addEventListener('change', handlePaymentChange));

        document.addEventListener('DOMContentLoaded', () => {
            initMap();
            handlePaymentChange();
        });
    </script>
@endpush