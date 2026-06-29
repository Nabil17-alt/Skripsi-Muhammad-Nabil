@extends('layouts.admin')

@section('page_title', 'Pesan Pembeli')

@section('content')
    <div class="mb-6">
        <div class="text-sm text-slate-500">Kelola semua pesan dan pertanyaan dari pelanggan Anda.</div>
    </div>

    @if($chats->count() > 0)
        <div class="table-wrapper">
            <table class="table-default">
                <thead>
                    <tr>
                        <th>Customer</th>
                        <th>Subjek</th>
                        <th>Pesan Terbaru</th>
                        <th class="text-right">Update Terakhir</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($chats as $chat)
                        <tr>
                            <td>
                                <p class="font-semibold text-slate-900">{{ $chat->user->name ?? '-' }}</p>
                                <p class="text-xs text-slate-500 mt-0.5">{{ $chat->user->email ?? '-' }}</p>
                            </td>
                            <td>
                                <p class="font-medium text-slate-900">{{ $chat->subject ?? 'Pesan Umum' }}</p>
                            </td>
                            <td>
                                @php
                                    $lastMessage = $chat->messages->last();
                                @endphp
                                @if($lastMessage)
                                    <p class="text-sm text-slate-700 truncate max-w-xs">
                                        <span
                                            class="text-slate-500">{{ $lastMessage->sender_type === 'admin' ? 'Anda:' : 'Mereka:' }}</span>
                                        {{ Str::limit($lastMessage->message, 40) }}
                                    </p>
                                @else
                                    <p class="text-sm text-slate-500">-</p>
                                @endif
                            </td>
                            <td class="text-right text-sm text-slate-600">{{ $chat->updated_at->diffForHumans() }}</td>
                            <td class="text-center">
                                <a href="{{ route('admin.chats.show', $chat) }}" class="btn-secondary text-xs">Buka</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($chats instanceof \Illuminate\Pagination\Paginator && $chats->hasPages())
            <div class="mt-6">
                {{ $chats->links() }}
            </div>
        @endif
    @else
        <div class="card card-body text-center py-12">
            <svg class="mx-auto h-12 w-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z">
                </path>
            </svg>
            <p class="text-slate-600 mt-4">Belum ada pesan dari pelanggan</p>
        </div>
    @endif
@endsection