<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nota Pembayaran #{{ $order->order_number }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                background-color: white !important;
            }
            .print-card {
                box-shadow: none !important;
                border: none !important;
                margin: 0 !important;
                padding: 0 !important;
                width: 100% !important;
            }
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-900 antialiased font-sans">
    <div class="max-w-4xl mx-auto my-4 sm:my-8 bg-white p-4 sm:p-8 rounded-2xl shadow-sm border border-slate-100 print-card">
        <!-- Action Buttons -->
        <div class="flex justify-between items-center mb-6 no-print">
            <a href="{{ route('payments.show', $order->order_number) }}" class="text-sm text-slate-500 hover:text-slate-800 flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali
            </a>
            <button onclick="window.print()" class="bg-emerald-600 text-white px-4 py-2 rounded-lg text-sm font-bold shadow-lg shadow-emerald-500/20 hover:bg-emerald-700 transition-colors flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                Cetak Nota
            </button>
        </div>

        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start border-b pb-6 mb-6 gap-4">
            <div>
                <h1 class="text-3xl font-black text-transparent bg-clip-text bg-gradient-to-r from-emerald-600 to-blue-600">ZIMAM</h1>
                <p class="text-xs text-slate-500 uppercase tracking-wider font-bold">Advertising</p>
                <div class="mt-4 text-sm text-slate-600">
                    <p class="font-bold">Zimam Advertising</p>
                    <p>Jl. Pemda, Pangkalan Kerinci Kota</p>
                    <p>Kec. Pangkalan Kerinci, Kabupaten Pelalawan, Riau</p>
                </div>
            </div>
            <div class="text-left sm:text-right w-full sm:w-auto">
                <h2 class="text-xl font-bold text-slate-800">NOTA PEMBAYARAN</h2>
                <p class="text-sm text-slate-500">No. Pesanan: #{{ $order->order_number }}</p>
                <p class="text-sm text-slate-500">Tanggal: {{ $order->created_at ? \Carbon\Carbon::parse($order->created_at)->format('d M Y') : '-' }}</p>
                
                <div class="mt-2 sm:mt-4">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700">
                        {{ strtoupper($order->payment_status) }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Customer & Payment Info -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-8">
            <div>
                <h3 class="text-xs text-slate-400 uppercase tracking-wider font-bold mb-2">Kepada:</h3>
                <p class="font-bold text-slate-800">{{ $order->user->name ?? '-' }}</p>
                <p class="text-sm text-slate-600">{{ $order->user->email ?? '-' }}</p>
                <p class="text-sm text-slate-600">{{ $order->user->phone ?? '-' }}</p>
            </div>
            <div class="text-left sm:text-right">
                <h3 class="text-xs text-slate-400 uppercase tracking-wider font-bold mb-2">Metode Pembayaran:</h3>
                <p class="font-bold text-slate-800 uppercase">{{ $payment->method->name ?? '-' }}</p>
                <p class="text-sm text-slate-600">Tipe: {{ str_replace('_', ' ', $payment->method->type ?? '-') }}</p>
            </div>
        </div>

        <!-- Items Table -->
        <div class="border rounded-lg overflow-x-auto mb-8">
            <table class="w-full text-sm min-w-[600px] sm:min-w-0">
                <thead class="bg-slate-50 border-b">
                    <tr>
                        <th class="py-3 px-4 text-left font-bold text-slate-600">Produk</th>
                        <th class="py-3 px-4 text-center font-bold text-slate-600">Jumlah</th>
                        <th class="py-3 px-4 text-right font-bold text-slate-600">Harga</th>
                        <th class="py-3 px-4 text-right font-bold text-slate-600">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @foreach($order->items as $item)
                        <tr>
                            <td class="py-4 px-4">
                                <p class="font-bold text-slate-800">{{ $item->product->name ?? 'Custom Product' }}</p>
                                @if($item->notes)
                                    <p class="text-xs text-slate-500 mt-1">Catatan: {{ $item->notes }}</p>
                                @endif
                                @if(($item->design_service_fee ?? 0) > 0)
                                    <p class="text-xs text-slate-500 mt-1">Biaya Desain: Rp {{ number_format($item->design_service_fee, 0, ',', '.') }}</p>
                                @endif
                            </td>
                            <td class="py-4 px-4 text-center text-slate-700">{{ $item->quantity }}</td>
                            <td class="py-4 px-4 text-right text-slate-700">Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                            <td class="py-4 px-4 text-right font-bold text-slate-800">
                                Rp {{ number_format(($item->unit_price * $item->quantity) + ($item->design_service_fee ?? 0), 0, ',', '.') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-slate-50 border-t font-bold">
                    <tr>
                        <td colspan="3" class="py-3 px-4 text-right text-slate-600">Total Tagihan:</td>
                        <td class="py-3 px-4 text-right text-emerald-600 text-lg">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Installment Details if applicable -->
        @if($payment && $payment->method && $payment->method->type === 'installment')
            <div class="border rounded-lg p-6 bg-slate-50">
                <h3 class="text-sm font-bold text-slate-800 mb-4">Detail Pembayaran Cicilan</h3>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between flex-col sm:flex-row gap-1">
                        <span class="text-slate-600">DP ({{ config('installments.dp_percent') }}%)</span>
                        <span class="font-bold text-slate-800">Rp {{ number_format($payment->amount, 0, ',', '.') }} 
                            <span class="text-xs text-emerald-600">({{ strtoupper($payment->status) }})</span>
                        </span>
                    </div>
                    @foreach($payment->installments ?? [] as $installment)
                        <div class="flex justify-between flex-col sm:flex-row gap-1">
                            <span class="text-slate-600">Cicilan Ke-{{ $installment->sequence }} (Jatuh Tempo: {{ \Carbon\Carbon::parse($installment->due_date)->format('d M Y') }})</span>
                            <span class="font-bold text-slate-800">Rp {{ number_format($installment->amount, 0, ',', '.') }}
                                <span class="text-xs {{ $installment->status === 'paid' ? 'text-emerald-600' : 'text-amber-600' }}">
                                    ({{ strtoupper($installment->status) }})
                                </span>
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Footer -->
        <div class="mt-12 text-center text-xs text-slate-400 border-t pt-6">
            <p>Terima kasih telah berbelanja di Zimam Advertising.</p>
            <p>Nota ini sah dan dihasilkan secara otomatis oleh sistem.</p>
        </div>
    </div>
</body>
</html>
