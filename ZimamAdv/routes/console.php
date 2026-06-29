<?php

use App\Models\Installment;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('installments:mark-overdue', function () {
    $affected = Installment::query()
        ->where('status', 'pending')
        ->whereDate('due_date', '<', now()->toDateString())
        ->update(['status' => 'overdue']);

    $this->info("Marked {$affected} installments as overdue.");
})->purpose('Mark overdue installments based on due_date');
