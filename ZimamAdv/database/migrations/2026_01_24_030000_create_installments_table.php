<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tb_installments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_id')->constrained('tb_payments')->onDelete('cascade');
            $table->unsignedTinyInteger('sequence'); // cicilan ke-1,2,...
            $table->decimal('amount', 15, 2);
            $table->date('due_date');
            $table->string('status')->default('pending'); // pending, paid, overdue
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_installments');
    }
};
