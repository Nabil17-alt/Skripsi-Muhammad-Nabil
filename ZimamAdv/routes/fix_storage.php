<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\File;

Route::get('/fix-storage', function () {
    date_default_timezone_set('Asia/Jakarta');
    $oldPath = storage_path('app/private/public');
    $newPath = storage_path('app/public');

    $report = [];

    if (File::exists($oldPath)) {
        // Move designs
        if (File::exists($oldPath . '/designs')) {
            if (!File::exists($newPath . '/designs')) {
                File::makeDirectory($newPath . '/designs', 0755, true);
            }
            $files = File::files($oldPath . '/designs');
            foreach ($files as $file) {
                $target = $newPath . '/designs/' . $file->getFilename();
                if (!File::exists($target)) {
                    File::copy($file->getRealPath(), $target);
                }
            }
            $report[] = "Files moved from private designs to public designs.";
        }

        // Move temp
        if (File::exists($oldPath . '/temp')) {
            if (!File::exists($newPath . '/temp')) {
                File::makeDirectory($newPath . '/temp', 0755, true);
            }
            $files = File::files($oldPath . '/temp');
            foreach ($files as $file) {
                $target = $newPath . '/temp/' . $file->getFilename();
                if (!File::exists($target)) {
                    File::copy($file->getRealPath(), $target);
                }
            }
            $report[] = "Files moved from private temp to public temp.";
        }
    } else {
        $report[] = "Private storage folder not found (maybe already moved).";
    }

    // Clean up database paths
    $chatMessages = \App\Models\ChatMessage::where('file_path', 'like', 'public/%')->get();
    foreach ($chatMessages as $msg) {
        $msg->update(['file_path' => str_replace('public/', '', $msg->file_path)]);
    }
    if ($chatMessages->count() > 0) {
        $report[] = "Cleaned up " . $chatMessages->count() . " chat message paths.";
    }

    $payments = \App\Models\Payment::where('payment_proof_path', 'like', 'public/%')->get();
    foreach ($payments as $p) {
        $p->update(['payment_proof_path' => str_replace('public/', '', $p->payment_proof_path)]);
    }
    
    $orderItems = \App\Models\OrderItem::where('design_file_path', 'like', 'public/%')->get();
    foreach ($orderItems as $item) {
        $item->update(['design_file_path' => str_replace('public/', '', $item->design_file_path)]);
    }

    return response()->json([
        'status' => 'success',
        'report' => $report,
        'message' => 'Perbaikan selesai. Silakan refresh halaman chat.'
    ]);
});
