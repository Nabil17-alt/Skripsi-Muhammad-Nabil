@extends('layouts.admin')

@section('page_title', 'Detail Pesan Pembeli')

@section('content')
    <div class="w-full">
        <!-- Header -->
        <div class="card card-header mb-6">
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1">Customer</p>
                <h2 class="text-2xl font-bold text-slate-900">{{ $chat->user->name ?? '-' }}</h2>
                <p class="text-sm text-slate-600 mt-1">{{ $chat->user->email ?? '-' }}</p>
            </div>
            <div class="text-right">
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1">Subjek</p>
                <p class="text-lg font-semibold text-slate-900">{{ $chat->subject ?? 'Pesan Umum' }}</p>
            </div>
        </div>

        <!-- Chat Container -->
        <div class="card mb-6">
            <div class="card-body">
                <div class="space-y-4 max-h-96 overflow-y-auto" id="chat-messages">
                    @foreach($chat->messages as $message)
                        <div class="flex {{ $message->sender_type === 'admin' ? 'justify-end' : 'justify-start' }}">
                            <div
                                class="max-w-md {{ $message->sender_type === 'admin' ? 'bg-sky-50 border border-sky-200' : 'bg-slate-100 border border-slate-200' }} rounded-lg p-3">
                                <div class="flex items-center justify-between mb-1">
                                    <span
                                        class="text-xs font-semibold {{ $message->sender_type === 'admin' ? 'text-sky-700' : 'text-slate-700' }}">
                                        {{ $message->sender_type === 'admin' ? 'Anda' : ($chat->user->name ?? 'Customer') }}
                                    </span>
                                    <span class="text-xs text-slate-500 ml-2">{{ $message->created_at->timezone('Asia/Jakarta')->format('H:i') }} WIB</span>
                                </div>
                                <p class="text-sm text-slate-900 whitespace-pre-wrap break-words">{{ $message->message }}</p>

                                @if($message->file_path)
                                    @php
                                        $ext = pathinfo($message->file_path, PATHINFO_EXTENSION);
                                        $isImage = in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg']);
                                    @endphp
                                    <div class="mt-2 pt-2 border-t {{ $message->sender_type === 'admin' ? 'border-sky-200' : 'border-slate-200' }}">
                                        @if($isImage)
                                            <a href="{{ asset('storage/' . $message->file_path) }}" target="_blank" class="block hover:opacity-90 transition-opacity">
                                                <img src="{{ asset('storage/' . $message->file_path) }}" alt="Lampiran" class="max-h-60 rounded-lg border {{ $message->sender_type === 'admin' ? 'border-sky-200' : 'border-slate-200' }} mt-1 shadow-sm">
                                            </a>
                                            <div class="mt-2">
                                                <a href="{{ asset('storage/' . $message->file_path) }}" download class="text-[10px] text-emerald-600 hover:text-emerald-700 underline flex items-center gap-1">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                                    Download Gambar
                                                </a>
                                            </div>
                                        @else
                                            <div class="flex flex-col gap-2">
                                                <a href="{{ asset('storage/' . $message->file_path) }}" target="_blank" class="flex items-center gap-2 px-3 py-2 bg-white/50 rounded border {{ $message->sender_type === 'admin' ? 'border-sky-200 text-sky-800' : 'border-slate-200 text-slate-800' }} hover:bg-white transition-colors text-sm">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                                    <span class="font-medium">File ({{ strtoupper($ext) }})</span>
                                                </a>
                                                <a href="{{ asset('storage/' . $message->file_path) }}" download class="btn-secondary text-[10px] py-1 text-center">
                                                    Klik untuk Download
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Reply Form -->
        <div class="card">
            <div class="card-header">
                <span>Kirim Balasan</span>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.chats.reply', $chat) }}" method="POST" enctype="multipart/form-data"
                    class="space-y-4">
                    @csrf

                    <div>
                        <label class="form-label">Pesan</label>
                        <textarea name="message" class="form-input @error('message') border-rose-500 @enderror" rows="4"
                            placeholder="Ketik balasan Anda..." required></textarea>
                        @error('message')
                            <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="form-label">Lampirkan Gambar (Opsional)</label>
                        <div class="relative border-2 border-dashed border-slate-300 rounded-lg p-4 text-center hover:border-sky-400 hover:bg-sky-50 transition-colors cursor-pointer"
                            onclick="document.getElementById('image-input').click()">
                            <svg class="mx-auto h-8 w-8 text-slate-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                </path>
                            </svg>
                            <p class="mt-2 text-sm text-slate-600"><span class="font-semibold text-sky-600">Klik</span> atau
                                drag & drop</p>
                            <p class="text-xs text-slate-500 mt-1">PNG, JPG, GIF hingga 3MB (untuk desain/revisi)</p>
                            <input type="file" id="image-input" name="image" class="hidden" accept="image/*"
                                onchange="previewImage(this)">
                        </div>
                        <div id="image-preview" class="mt-2"></div>
                        @error('image')
                            <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end gap-2 pt-4 border-t border-slate-200">
                        <a href="{{ route('admin.chats.index') }}" class="btn-secondary text-xs">Tutup</a>
                        <button type="submit" class="btn-primary text-xs">Kirim Balasan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Auto scroll ke pesan terbaru
        const messagesContainer = document.getElementById('chat-messages');
        if (messagesContainer) {
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }

        function previewImage(input) {
            const preview = document.getElementById('image-preview');
            preview.innerHTML = '';

            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    const div = document.createElement('div');
                    div.className = 'relative inline-block mt-2';
                    div.innerHTML = `
                            <div class="inline-block rounded-lg overflow-hidden border border-slate-200">
                                <img src="${e.target.result}" alt="Preview" class="h-24 object-cover">
                            </div>
                            <button type="button" class="absolute -top-2 -right-2 bg-rose-600 text-white rounded-full p-1 hover:bg-rose-700" onclick="clearImage()">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                            </button>
                        `;
                    preview.appendChild(div);
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        function clearImage() {
            document.getElementById('image-input').value = '';
            document.getElementById('image-preview').innerHTML = '';
        }
    </script>
@endsection