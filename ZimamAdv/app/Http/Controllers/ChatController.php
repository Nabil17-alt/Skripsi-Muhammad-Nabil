<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Chat;
use App\Models\ChatMessage;

class ChatController extends Controller
{
    public function index()
    {
        $user = auth()->user();



        $messages = ChatMessage::whereHas('chat', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->orderBy('created_at', 'asc')->get();

        return view('frontend.chat.index', compact('messages'));
    }

    public function send(Request $request)
    {
        $request->validate([
            'message' => 'nullable|string',
            'image' => 'nullable|image|max:3072', // maks 3MB
        ]);

        $userId = $request->user()->id;

        // Pastikan minimal ada pesan teks atau gambar
        if (!$request->filled('message') && !$request->hasFile('image')) {
            return back()->withErrors([
                'message' => 'Tulis pesan atau pilih gambar untuk dikirim.',
            ])->withInput();
        }

        $chat = Chat::firstOrCreate([
            'user_id' => $userId,
            'order_id' => null,
        ]);

        $filePath = null;
        if ($request->hasFile('image')) {
            $filePath = $request->file('image')->store('chat_images', 'public');
        }

        ChatMessage::create([
            'chat_id' => $chat->id,
            'sender_type' => 'customer',
            'sender_id' => $userId,
            'message' => $request->message,
            'file_path' => $filePath,
        ]);

        return redirect()->route('chat.index');
    }
}
