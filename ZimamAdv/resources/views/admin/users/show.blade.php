@extends('layouts.admin')

@section('page_title', 'Detail Akun & Riwayat Pesanan')

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-1 space-y-4">
            <div class="card">
                <div class="card-header flex items-center justify-between">
                    <span>Data Akun</span>
                </div>
                <div class="card-body space-y-2 text-sm">
                    <div>
                        <div class="text-xs text-slate-500">Nama</div>
                        <div class="font-medium">{{ $user->name }}</div>
                    </div>
                    <div>
                        <div class="text-xs text-slate-500">Email</div>
                        <div>{{ $user->email }}</div>
                    </div>
                    <div>
                        <div class="text-xs text-slate-500">No. HP</div>
                        <div>{{ $user->phone ?? '-' }}</div>
                    </div>
                    <div>
                        <div class="text-xs text-slate-500">Role</div>
                        <div>{{ $user->role->name ?? '-' }}</div>
                    </div>
                    <div>
                        <div class="text-xs text-slate-500">Status</div>
                        <span class="badge-status {{ $user->is_active ? 'badge-success' : 'badge-muted' }}">
                            {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </div>
                    @if(($user->role->name ?? '') === 'customer')
                    <div>
                        <div class="text-xs text-slate-500">Tenor Cicilan yang Diizinkan</div>
                        <span class="badge-status bg-blue-50 text-blue-700 font-bold">
                            {{ $user->allowed_tenor }} Bulan
                        </span>
                    </div>
                    @endif
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <span>Aksi Akun</span>
                </div>
                <div class="card-body space-y-3 text-sm">
                    <form action="{{ route('admin.users.reset-password', $user) }}" method="POST"
                        onsubmit="return confirm('Reset password ke password123?');">
                        @csrf
                        <button type="submit" class="btn-secondary w-full text-xs">Reset Password</button>
                    </form>
                    <a href="{{ route('admin.users.edit', $user) }}"
                        class="btn-primary w-full text-xs text-center block">Edit Akun</a>
                    <a href="{{ route('admin.users.index') }}"
                        class="btn-secondary w-full text-xs text-center block">Kembali ke Daftar Akun</a>
                </div>
            </div>

            @if(session('status'))
                <div class="card border border-emerald-500 bg-emerald-50">
                    <div class="card-body text-sm text-emerald-800">
                        {{ session('status') }}
                    </div>
                </div>
            @endif
        </div>

        <div class="lg:col-span-2">
            <div class="card">
                <div class="card-header">
                    <span>Riwayat Pesanan Customer</span>
                </div>
                <div class="card-body">
                    <div class="table-wrapper">
                        <table class="table-default">
                            <thead>
                                <tr>
                                    <th>No. Pesanan</th>
                                    <th>Tanggal</th>
                                    <th class="text-right">Total</th>
                                    <th>Status Produksi</th>
                                    <th>Status Pembayaran</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($orders as $order)
                                    <tr>
                                        <td>{{ $order->order_number }}</td>
                                        <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                        <td class="text-right">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</td>
                                        <td>
                                            <span class="badge-status">
                                                {{ ucfirst(str_replace('_', ' ', $order->production_status)) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge-status">
                                                {{ ucfirst(str_replace('_', ' ', $order->payment_status)) }}
                                            </span>
                                        </td>
                                        <td class="text-right">
                                            <a href="{{ route('admin.orders.show', $order) }}"
                                                class="text-xs text-sky-600 hover:underline">Detail</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-sm text-slate-500 py-4">Belum ada pesanan.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $orders->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection