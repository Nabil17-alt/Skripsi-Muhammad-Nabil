@extends('layouts.admin')

@section('page_title', 'Pengguna')

@section('content')
    <div class="flex justify-between items-center mb-4">
        <div class="text-sm text-slate-500">Kelola Akun Pengguna.</div>
        <a href="{{ route('admin.users.create') }}" class="btn-primary text-xs">Tambah Akun</a>
    </div>

    <div class="table-wrapper">
        <table class="table-default">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th class="text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->role->name ?? '-' }}</td>
                        <td>
                            <span
                                class="badge-status {{ $user->is_active ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-50 text-rose-700' }}">
                                {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </td>
                        <td class="text-right flex items-center justify-end gap-2">
                            @php
                                $currentUserRole = auth()->user()->role->name ?? '';
                                $canManage = false;
                                if ($currentUserRole === 'admin' && ($user->role->name ?? '') === 'customer') {
                                    $canManage = true;
                                } elseif ($currentUserRole === 'pimpinan' && ($user->role->name ?? '') === 'admin') {
                                    $canManage = true;
                                }
                            @endphp

                            @if($canManage)
                                <a href="{{ route('admin.users.show', $user) }}" class="btn-secondary text-xs">Detail</a>
                                <a href="{{ route('admin.users.edit', $user) }}" class="btn-secondary text-xs">Edit</a>
                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-rose-600 text-xs" onclick="return confirm('Hapus akun ini?')">Hapus</button>
                                </form>
                            @else
                                <span class="text-slate-400 text-xs italic">Akses Terbatas</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-sm text-slate-500 py-4">Belum ada user.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection