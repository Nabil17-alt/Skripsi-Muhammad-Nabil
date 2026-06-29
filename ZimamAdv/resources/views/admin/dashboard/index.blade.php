@extends('layouts.admin')

@section('page_title', 'Dashboard')

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-2xl p-6 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 flex items-center gap-4 hover:shadow-[0_8px_30px_rgb(16,185,129,0.1)] transition-shadow">
            <div class="h-12 w-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
            </div>
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">Total Pesanan</p>
                <p class="text-2xl font-black text-slate-800">{{ $totalOrders }}</p>
            </div>
        </div>
        <div id="revenue-card" class="bg-white rounded-2xl p-6 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 flex items-center gap-4 hover:shadow-[0_8px_30px_rgb(16,185,129,0.1)] transition-all cursor-pointer relative overflow-hidden select-none active:scale-[0.98]">
            <div class="h-12 w-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div class="flex-1 min-w-0">
                <!-- Tampilan Pendapatan Keseluruhan (Default) -->
                <div id="revenue-overall-view" class="transition-all duration-300">
                    <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-0.5">Total Pendapatan (Semua)</p>
                    <p class="text-2xl font-black text-slate-800 leading-tight">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
                    <p class="text-[10px] text-emerald-600 font-semibold mt-1 flex items-center gap-0.5">
                        <span>Tekan untuk lihat Bulan Ini</span> &rarr;
                    </p>
                </div>
                <!-- Tampilan Pendapatan Bulan Ini (Hidden by default) -->
                <div id="revenue-month-view" class="hidden transition-all duration-300">
                    <p class="text-[10px] font-bold uppercase tracking-wider text-emerald-600 mb-0.5">Pendapatan Bulan Ini</p>
                    <p class="text-2xl font-black text-slate-800 leading-tight">Rp {{ number_format($revenueThisMonth, 0, ',', '.') }}</p>
                    <button type="button" id="btn-overall-revenue" class="text-[10px] font-bold text-blue-600 hover:text-blue-800 hover:underline mt-1 flex items-center gap-0.5">
                        &larr; Lihat Keseluruhan
                    </button>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-6 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 flex items-center gap-4 hover:shadow-[0_8px_30px_rgb(16,185,129,0.1)] transition-shadow">
            <div class="h-12 w-12 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            </div>
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">Order Hari Ini</p>
                <p class="text-2xl font-black text-slate-800">{{ $ordersToday }}</p>
            </div>
        </div>
        <div class="bg-gradient-to-br from-emerald-500 to-blue-600 rounded-2xl p-6 shadow-lg shadow-emerald-500/30 flex items-center gap-4 text-white hover:scale-105 transition-transform cursor-default relative overflow-hidden">
            <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-white opacity-10 rounded-full blur-xl"></div>
            <div class="h-12 w-12 rounded-xl bg-white/20 backdrop-blur-sm flex items-center justify-center relative z-10">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
            </div>
            <div class="relative z-10">
                <p class="text-xs font-bold uppercase tracking-wider text-emerald-100 mb-1">Pendapatan Hari Ini</p>
                <p class="text-2xl font-black text-white">Rp {{ number_format($revenueToday, 0, ',', '.') }}</p>
            </div>
        </div>
    </div>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <div class="bg-white rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 lg:col-span-2 overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                <div>
                    <h3 class="font-bold text-slate-800 text-lg">Grafik Pendapatan Real-time</h3>
                    <div class="text-xs text-slate-500 mt-1 flex items-center gap-1" id="chart-breadcrumb">
                        <span class="cursor-pointer text-emerald-600 font-semibold hover:underline" onclick="loadChartData('year')">Tahunan</span>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <button onclick="downloadChartPDF()" class="btn-secondary py-1.5 px-3 text-xs flex items-center gap-2 bg-white">
                        <svg class="w-4 h-4 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        Download PDF
                    </button>
                    <span class="text-[10px] font-bold px-2 py-1 bg-blue-50 text-blue-600 rounded-full uppercase tracking-wider">Drill-down</span>
                </div>
            </div>
            <div class="p-6 pt-2">
                <div id="apexSalesChart" class="w-full h-80"></div>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 overflow-hidden flex flex-col">
            <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/50">
                <h3 class="font-bold text-slate-800">Status Pesanan Terkini</h3>
            </div>
            <div class="p-6 text-sm space-y-4 flex-1">
                @php
                    $labels = [
                        'menunggu_pembayaran' => 'Menunggu Pembayaran',
                        'diproses' => 'Sedang Diproses',
                        'desain' => 'Tahap Desain',
                        'revisi' => 'Proses Revisi',
                        'cetak' => 'Sedang Dicetak',
                        'selesai' => 'Sudah Selesai',
                    ];
                    $icons = [
                        'menunggu_pembayaran' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>',
                        'diproses' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>',
                        'desain' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>',
                        'revisi' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>',
                        'cetak' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>',
                        'selesai' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>',
                    ];
                @endphp
                @foreach($labels as $key => $label)
                    <div class="flex items-center justify-between group cursor-default p-2 rounded-xl hover:bg-slate-50 transition-all">
                        <div class="flex items-center gap-3 text-slate-600 group-hover:text-emerald-600 transition-colors">
                            <svg class="w-4 h-4 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $icons[$key] !!}</svg>
                            <span class="font-medium">{{ $label }}</span>
                        </div>
                        <span class="font-black bg-slate-100 px-3 py-1 rounded-lg text-slate-700 group-hover:bg-emerald-500 group-hover:text-white transition-all shadow-sm">{{ $statusCounts[$key] ?? 0 }}</span>
                    </div>
                @endforeach
                
                <div class="mt-auto pt-6 border-t border-slate-100">
                    <p class="text-[10px] text-slate-400 mb-1 uppercase tracking-widest font-black">Profit Bulan Ini</p>
                    <div class="flex items-baseline gap-1">
                        <span class="text-2xl font-black text-emerald-600">Rp {{ number_format($revenueThisMonth, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 overflow-hidden mb-8">
        <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
            <h3 class="font-bold text-slate-800 flex items-center gap-2">
                <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
                Produk Terlaris
            </h3>
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Berdasarkan Jumlah Order</span>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Tabel Produk Terlaris -->
                <div class="lg:col-span-2 flex flex-col">
                    <div class="overflow-x-auto h-full flex flex-col">
                        <table class="w-full h-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50/50 text-slate-500 text-[10px] font-black uppercase tracking-widest">
                                    <th class="px-4 py-3 border-b border-slate-100">Nama Produk</th>
                                    <th class="px-4 py-3 border-b border-slate-100 text-center">Jumlah Order</th>
                                    <th class="px-4 py-3 border-b border-slate-100 text-right">Persentase</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @php
                                    $totalTopOrders = $topProducts->sum('order_items_count');
                                @endphp
                                @forelse($topProducts as $product)
                                    @php
                                        $percentage = $totalTopOrders > 0 ? round(($product->order_items_count / $totalTopOrders) * 100, 1) : 0;
                                    @endphp
                                    <tr class="hover:bg-slate-50/50 transition-colors group">
                                        <td class="px-4 py-3.5 text-sm font-bold text-slate-700 group-hover:text-emerald-600 transition-colors">{{ $product->name }}</td>
                                        <td class="px-4 py-3.5 text-sm font-semibold text-slate-600 text-center">{{ $product->order_items_count }}</td>
                                        <td class="px-4 py-3.5 text-sm text-right">
                                            <span class="font-black text-emerald-600">{{ $percentage }}%</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-4 py-8 text-center text-sm text-slate-500">Belum ada data penjualan produk.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Grafik Donut Persen -->
                <div class="flex justify-center items-center">
                    @if($totalTopOrders > 0)
                        <div id="apexProductsDonutChart" class="w-full max-w-[360px]"></div>
                    @else
                        <div class="text-slate-400 text-sm text-center py-8">Grafik tidak tersedia karena belum ada data penjualan.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.23/jspdf.plugin.autotable.min.js"></script>
        <script>
            let currentLevel = 'year';
            let currentYear = new Date().getFullYear();
            let currentMonth = null;
            let currentWeek = null;
            let chart = null;

            const formatRupiah = (value) => {
                return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(value);
            };

            const loadChartData = async (level, year = null, month = null, week = null) => {
                currentLevel = level;
                if (year !== null) currentYear = year;
                if (month !== null) currentMonth = month;
                if (week !== null) currentWeek = week;

                // Update Breadcrumb
                const bc = document.getElementById('chart-breadcrumb');
                let html = `<span class="cursor-pointer text-emerald-600 font-semibold hover:underline" onclick="loadChartData('year')">Tahunan</span>`;
                
                if (level === 'month' || level === 'week' || level === 'day') {
                    html += ` <span class="text-slate-400 mx-1">/</span> <span class="cursor-pointer text-emerald-600 font-semibold hover:underline" onclick="loadChartData('month', ${currentYear})">${currentYear}</span>`;
                }
                if (level === 'week' || level === 'day') {
                    const monthNames = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
                    html += ` <span class="text-slate-400 mx-1">/</span> <span class="cursor-pointer text-emerald-600 font-semibold hover:underline" onclick="loadChartData('week', ${currentYear}, ${currentMonth})">${monthNames[currentMonth]}</span>`;
                }
                if (level === 'day') {
                    html += ` <span class="text-slate-400 mx-1">/</span> <span class="text-slate-800 font-bold">Minggu ${currentWeek}</span>`;
                }
                bc.innerHTML = html;

                let url = `/admin/dashboard/sales-data?level=${level}&year=${currentYear}`;
                if (currentMonth) url += `&month=${currentMonth}`;
                if (currentWeek) url += `&week=${currentWeek}`;

                try {
                    const response = await fetch(url);
                    const data = await response.json();

                    const chartOptions = {
                        series: [{
                            name: 'Pendapatan',
                            data: data
                        }],
                        chart: {
                            type: 'bar',
                            height: 320,
                            fontFamily: 'Inter, sans-serif',
                            animations: {
                                enabled: true,
                                easing: 'easeinout',
                                speed: 600,
                            },
                            toolbar: { show: false },
                            events: {
                                dataPointSelection: function(event, chartContext, config) {
                                    const selectedData = config.w.config.series[config.seriesIndex].data[config.dataPointIndex];
                                    if (currentLevel === 'year') {
                                        loadChartData('month', selectedData.year_val);
                                    } else if (currentLevel === 'month') {
                                        loadChartData('week', currentYear, selectedData.month_num);
                                    } else if (currentLevel === 'week') {
                                        loadChartData('day', currentYear, currentMonth, selectedData.week_num);
                                    }
                                }
                            }
                        },
                        plotOptions: {
                            bar: {
                                borderRadius: 8,
                                columnWidth: '50%',
                                distributed: true,
                            }
                        },
                        colors: ['#10b981', '#3b82f6', '#f59e0b', '#6366f1', '#ec4899', '#8b5cf6', '#14b8a6'],
                        dataLabels: { enabled: false },
                        legend: { show: false },
                        xaxis: {
                            type: 'category',
                            labels: {
                                style: { colors: '#64748b', fontWeight: 600, fontSize: '10px' }
                            }
                        },
                        yaxis: {
                            labels: {
                                style: { colors: '#64748b', fontWeight: 600 },
                                formatter: function (val) {
                                    if (val >= 1000000) return "Rp " + (val / 1000000).toFixed(1) + " Jt";
                                    if (val >= 1000) return "Rp " + (val / 1000).toFixed(0) + " Rb";
                                    return "Rp " + val;
                                }
                            }
                        },
                        tooltip: {
                            theme: 'dark',
                            y: { formatter: (val) => formatRupiah(val) }
                        }
                    };

                    if (!chart) {
                        chart = new ApexCharts(document.querySelector("#apexSalesChart"), chartOptions);
                        chart.render();
                    } else {
                        chart.updateOptions(chartOptions);
                    }
                } catch (error) {
                    console.error('Error fetching chart data:', error);
                }
            };

            const downloadChartPDF = async () => {
                const { jsPDF } = window.jspdf;
                const doc = new jsPDF('p', 'mm', 'a4');
                const timestamp = new Date().toLocaleString('id-ID');
                const monthNames = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
                
                // Header
                doc.setFontSize(22);
                doc.setTextColor(16, 185, 129); // Emerald
                doc.text("Laporan Pendapatan Zimam Advertising", 105, 20, { align: "center" });
                
                doc.setFontSize(10);
                doc.setTextColor(100, 116, 139);
                doc.text(`Dicetak pada: ${timestamp}`, 105, 28, { align: "center" });
                doc.line(20, 32, 190, 32);

                let yPos = 45;
                const targetMonth = currentMonth || (new Date().getMonth() + 1);
                const targetWeek = currentWeek || 1;

                const reportSections = [
                    { level: 'year', title: "Ringkasan Pendapatan 5 Tahun Terakhir", params: `&year=${currentYear}` },
                    { level: 'month', title: `Detail Pendapatan Bulanan (Tahun ${currentYear})`, params: `&year=${currentYear}` },
                    { level: 'week', title: `Detail Pendapatan Mingguan (Bulan ${monthNames[targetMonth]} ${currentYear})`, params: `&year=${currentYear}&month=${targetMonth}` }
                ];

                for (const section of reportSections) {
                    const url = `/admin/dashboard/sales-data?level=${section.level}${section.params}`;
                    try {
                        const response = await fetch(url);
                        const data = await response.json();
                        
                        if (yPos > 240) {
                            doc.addPage();
                            yPos = 20;
                        }

                        doc.setFontSize(14);
                        doc.setTextColor(30, 41, 59);
                        doc.text(section.title, 20, yPos);
                        yPos += 7;

                        const tableData = data.map(item => [item.x, formatRupiah(item.y)]);
                        
                        doc.autoTable({
                            startY: yPos,
                            head: [['Periode', 'Total Pendapatan']],
                            body: tableData,
                            theme: 'striped',
                            headStyles: { fillStyle: [16, 185, 129], textColor: 255, fontStyle: 'bold' },
                            margin: { left: 20, right: 20 },
                            didDrawPage: function(data) {
                                yPos = data.cursor.y + 15;
                            }
                        });
                    } catch (e) {
                        console.error("Failed to fetch section: " + section.level, e);
                    }
                }

                doc.save(`Laporan_Pendapatan_Lengkap_${currentYear}.pdf`);
            };

            // Init
            document.addEventListener('DOMContentLoaded', () => {
                loadChartData('year');

                // Toggle Revenue Card
                const revenueCard = document.getElementById('revenue-card');
                const revenueOverallView = document.getElementById('revenue-overall-view');
                const revenueMonthView = document.getElementById('revenue-month-view');
                const btnOverallRevenue = document.getElementById('btn-overall-revenue');

                if (revenueCard && revenueOverallView && revenueMonthView && btnOverallRevenue) {
                    revenueCard.addEventListener('click', function(e) {
                        if (e.target.closest('#btn-overall-revenue')) {
                            return;
                        }
                        if (revenueMonthView.classList.contains('hidden')) {
                            revenueOverallView.classList.add('hidden');
                            revenueMonthView.classList.remove('hidden');
                        }
                    });

                    btnOverallRevenue.addEventListener('click', function(e) {
                        e.stopPropagation();
                        revenueMonthView.classList.add('hidden');
                        revenueOverallView.classList.remove('hidden');
                    });
                }

                // Donut Chart for Top Products
                @php
                    $totalTopOrders = isset($topProducts) ? $topProducts->sum('order_items_count') : 0;
                    $topProductsMapped = isset($topProducts) ? $topProducts->map(function($product) use ($totalTopOrders) {
                        return [
                            'name' => $product->name,
                            'count' => $product->order_items_count,
                            'percentage' => $totalTopOrders > 0 ? round(($product->order_items_count / $totalTopOrders) * 100, 1) : 0
                        ];
                    }) : [];
                @endphp
                const topProductsData = @json($topProductsMapped);

                if (topProductsData.length > 0 && document.querySelector("#apexProductsDonutChart")) {
                    const donutOptions = {
                        series: topProductsData.map(item => item.count),
                        labels: topProductsData.map(item => item.name),
                        chart: {
                            type: 'donut',
                            height: 280,
                            fontFamily: 'Inter, sans-serif',
                            animations: {
                                enabled: true,
                                animateScale: true
                            }
                        },
                        colors: ['#10b981', '#3b82f6', '#f59e0b', '#6366f1', '#ec4899'],
                        legend: {
                            position: 'bottom',
                            horizontalAlign: 'center',
                            fontSize: '11px',
                            fontWeight: 500,
                            labels: { colors: '#64748b' }
                        },
                        dataLabels: {
                            enabled: true,
                            formatter: function (val) {
                                return val.toFixed(1) + "%"
                            },
                            style: {
                                fontSize: '11px',
                                fontFamily: 'Inter, sans-serif',
                                fontWeight: 'bold'
                            }
                        },
                        plotOptions: {
                            pie: {
                                donut: {
                                    size: '65%',
                                    labels: {
                                        show: true,
                                        name: {
                                            show: true,
                                            fontSize: '13px',
                                            fontWeight: 600,
                                            color: '#64748b'
                                        },
                                        value: {
                                            show: true,
                                            fontSize: '18px',
                                            fontWeight: 800,
                                            color: '#1e293b',
                                            formatter: function (val) {
                                                return val + " Order"
                                            }
                                        },
                                        total: {
                                            show: true,
                                            label: 'Total Order',
                                            color: '#64748b',
                                            formatter: function (w) {
                                                return w.globals.seriesTotals.reduce((a, b) => a + b, 0) + " Order"
                                            }
                                        }
                                    }
                                }
                            }
                        },
                        tooltip: {
                            theme: 'dark',
                            y: {
                                formatter: function (val) {
                                    return val + " Order"
                                }
                            }
                        }
                    };

                    const donutChart = new ApexCharts(document.querySelector("#apexProductsDonutChart"), donutOptions);
                    donutChart.render();
                }
            });
        </script>
    @endpush
@endsection