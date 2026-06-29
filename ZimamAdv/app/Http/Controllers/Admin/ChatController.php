<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\ChatMessage;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function index()
    {
        // Ambil ID chat terbaru untuk setiap user
        $latestChatIds = Chat::selectRaw('MAX(id) as id')
            ->groupBy('user_id')
            ->pluck('id');

        $chats = Chat::with(['user', 'messages'])
            ->whereIn('id', $latestChatIds)
            ->latest('updated_at')
            ->paginate(20);

        return view('admin.chats.index', compact('chats'));
    }

    public function show(Chat $chat)
    {
        // Load semua pesan dari user ini (lintas semua chat room milik user tersebut)
        $messages = ChatMessage::whereHas('chat', function ($query) use ($chat) {
            $query->where('user_id', $chat->user_id);
        })->orderBy('created_at', 'asc')->get();

        // Tempelkan messages ke object chat agar blade tetap kompatibel
        $chat->setRelation('messages', $messages);

        return view('admin.chats.show', compact('chat'));
    }

    public function reply(Request $request, Chat $chat)
    {
        $request->validate([
            'message' => 'nullable|string',
            'image' => 'nullable|image|max:3072', // maks 3MB
        ]);

        $adminId = $request->user()->id;

        if (!$request->filled('message') && !$request->hasFile('image')) {
            return back()->withErrors([
                'message' => 'Tulis balasan atau pilih gambar untuk dikirim.',
            ])->withInput();
        }

        // Pastikan kita membalas ke chat room utama (order_id null) milik user tersebut
        $mainChat = Chat::firstOrCreate([
            'user_id' => $chat->user_id,
            'order_id' => null,
        ]);

        $filePath = null;
        if ($request->hasFile('image')) {
            $filePath = $request->file('image')->store('chat_images', 'public');
        }

        ChatMessage::create([
            'chat_id' => $mainChat->id,
            'sender_type' => 'admin',
            'sender_id' => $adminId,
            'message' => $request->message,
            'file_path' => $filePath,
        ]);

        return redirect()->route('admin.chats.show', $chat);
    }
}
