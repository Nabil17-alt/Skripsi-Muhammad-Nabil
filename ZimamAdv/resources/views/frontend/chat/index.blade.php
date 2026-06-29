@extends('layouts.frontend')

@section('title', 'Chat dengan Admin - Zimam Advertising')

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        <div class="card lg:col-span-1">
            <div class="card-header">
                <h2 class="text-base font-semibold">Pesan ke Admin</h2>
            </div>
            <div class="card-body">
                <form action="{{ route('chat.send') }}" method="POST" enctype="multipart/form-data"
                    class="space-y-3 text-sm">
                    @csrf
                    <div>
                        <label class="form-label">Pesan</label>
                        <textarea name="message" class="form-textarea" rows="4"
                            placeholder="Tulis pesan Anda..."></textarea>
                    </div>
                    <div>
                        <label class="form-label">Lampirkan Gambar (opsional)</label>
                        <input type="file" name="image" accept="image/*" class="form-input text-sm">
                        <p class="text-[11px] text-slate-500 mt-1">Gunakan untuk kirim contoh desain / revisian (maks 3MB).
                        </p>
                    </div>
                    <button class="btn-primary">Kirim</button>
                </form>
            </div>
        </div>

        <div class="card lg:col-span-2">
            <div id="chat-container" class="card-body text-sm space-y-4 max-h-[500px] overflow-y-auto p-4 bg-slate-50/50 flex flex-col">
                @forelse($messages as $msg)
                    @php
                        $isCustomer = $msg->sender_type === 'customer';
                    @endphp
                    <div class="flex {{ $isCustomer ? 'justify-end' : 'justify-start' }}">
                        <div class="max-w-xs {{ $isCustomer ? 'bg-sky-50 border border-sky-200' : 'bg-slate-100 border border-slate-200' }} rounded-lg p-3">
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-xs font-semibold {{ $isCustomer ? 'text-sky-700' : 'text-slate-700' }}">
                                    {{ $isCustomer ? 'Anda' : 'Admin' }}
                                </span>
                                <span class="text-xs text-slate-500 ml-2">{{ $msg->created_at->timezone('Asia/Jakarta')->format('H:i') }} WIB</span>
                            </div>
                            <p class="text-sm text-slate-900 whitespace-pre-wrap break-words">{{ $msg->message }}</p>

                            @if($msg->file_path)
                                @php
                                    $ext = pathinfo($msg->file_path, PATHINFO_EXTENSION);
                                    $isImage = in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg']);
                                @endphp
                                <div class="mt-2 pt-2 border-t {{ $isCustomer ? 'border-sky-200' : 'border-slate-200' }}">
                                    <div class="mt-2">
                                        @if($isImage)
                                            <a href="{{ asset('storage/' . $msg->file_path) }}" target="_blank" class="block hover:opacity-90 transition-opacity">
                                                <img src="{{ asset('storage/' . $msg->file_path) }}" alt="Lampiran" class="max-h-60 rounded-lg border {{ $isCustomer ? 'border-sky-200' : 'border-slate-200' }} mt-1 shadow-sm">
                                            </a>
                                            <div class="mt-1">
                                                <a href="{{ asset('storage/' . $msg->file_path) }}" download class="text-[10px] text-emerald-600 hover:text-emerald-700 underline flex items-center gap-1">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                                    Download Gambar
                                                </a>
                                            </div>
                                        @else
                                            <div class="flex flex-col gap-1 w-full">
                                                <a href="{{ asset('storage/' . $msg->file_path) }}" target="_blank" class="flex items-center gap-2 px-3 py-2 bg-white/50 rounded border {{ $isCustomer ? 'border-sky-200 text-sky-800' : 'border-slate-200 text-slate-800' }} hover:bg-white transition-colors text-sm">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                                    <span class="font-medium">File ({{ strtoupper($ext) }})</span>
                                                </a>
                                                <a href="{{ asset('storage/' . $msg->file_path) }}" download class="text-[10px] text-slate-500 hover:text-sky-600 underline">
                                                    Download File
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-center text-slate-500 py-10">Belum ada percakapan terkait pesanan. Mulai kirim pesan kepada admin jika ada yang perlu didiskusikan.</div>
                @endforelse
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Auto scroll chat to bottom
    window.addEventListener('DOMContentLoaded', () => {
        const container = document.getElementById('chat-container');
        if (container) {
            container.scrollTop = container.scrollHeight;
        }
    });
</script>
@endpush