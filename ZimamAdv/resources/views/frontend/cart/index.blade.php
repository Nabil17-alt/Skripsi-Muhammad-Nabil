@extends('layouts.frontend')

@section('title', 'Keranjang Belanja - Zimam Advertising')

@section('content')
    <div class="card">
        <div class="card-header flex items-center justify-between">
            <h2 class="text-base font-semibold">Keranjang Belanja</h2>
        </div>
        <div class="card-body">
            @if(empty($cart))
                <p class="text-sm text-slate-600">Keranjang Anda kosong.</p>
            @else
                <form action="{{ route('cart.update') }}" method="POST" class="space-y-4">
                    @csrf
                    <div class="table-wrapper">
                        <table class="table-default">
                            <thead>
                                <tr>
<th>Produk</th>
    <th class="text-right">Harga</th>
    <th class="text-center">Jumlah</th>
    <th class="text-right">Subtotal</th>
    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                 @php $total = 0; @endphp
                                 @foreach($cart as $key => $item)
                                     @php 
                                         $sub = $item['price'] * $item['quantity'];
                                         if (($item['design_option'] ?? 'custom') === 'service') {
                                             $sub += ($item['design_service_fee'] ?? 0);
                                         }
                                         $total += $sub; 
                                     @endphp
                                     <tr>
                                        <td>
                                            <div class="font-medium">{{ $item['name'] }}</div>
                                             <div class="text-xs text-slate-500 mt-1">
                                                 Opsi desain:
                                                 <span class="font-semibold">
                                                     @if(($item['design_option'] ?? 'custom') === 'service')
                                                         Jasa desain Zimam (+Rp {{ number_format($item['design_service_fee'] ?? 0, 0, ',', '.') }})
                                                     @else
                                                         Desain sendiri
                                                     @endif
                                                 </span>
                                             </div>
                                            @if(!empty($item['notes']))
                                                <div class="text-xs text-slate-500 mt-1">Catatan: {{ $item['notes'] }}</div>
                                            @endif
                                            @if(!empty($item['design_file']))
                                                <div class="text-xs text-emerald-600 font-semibold mt-1 flex items-center gap-1">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                                                    File Desain Terlampir
                                                </div>
                                            @endif
                                        </td>
                                        <td class="text-right">Rp {{ number_format($item['price'], 0, ',', '.') }}</td>
                                        <td class="text-center">
                                            <input type="number" name="items[{{ $key }}]" value="{{ $item['quantity'] }}" min="1"
                                                class="form-input w-20 mx-auto" onchange="this.form.submit()">
                                        </td>
                                        <td class="text-right">Rp {{ number_format($sub, 0, ',', '.') }}</td>
                                        <td class="text-center">
                                            <button type="submit" form="delete-form-{{ $key }}" class="text-red-600 text-xs font-medium">Hapus</button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
                        <div class="text-sm">
                            <span class="text-slate-500">Total Belanja</span>
                            <div class="text-lg font-semibold text-slate-900">Rp {{ number_format($total, 0, ',', '.') }}</div>
                        </div>
                        <div class="flex gap-2">
                            <a href="{{ route('checkout.index') }}" class="btn-primary py-3 px-8 text-base font-bold shadow-xl shadow-blue-500/20">Lanjut Checkout</a>
                        </div>
                    </div>
                </form>
@foreach($cart as $key => $item)
    <form id="delete-form-{{ $key }}" action="{{ route('cart.destroy') }}" method="POST" style="display:none">
        @csrf
        <input type="hidden" name="key" value="{{ $key }}">
    </form>
@endforeach
            @endif
        </div>
    </div>
@endsection