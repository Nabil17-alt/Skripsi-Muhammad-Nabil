<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('tb_bank_accounts', function (Blueprint $table) {
            $table->foreignId('payment_method_id')
                ->nullable()
                ->after('id')
                ->constrained('tb_payment_methods');

            $table->string('image_path')->nullable()->after('account_holder');
        });
    }

    public function down(): void
    {
        Schema::table('tb_bank_accounts', function (Blueprint $table) {
            $table->dropForeign(['payment_method_id']);
            $table->dropColumn(['payment_method_id', 'image_path']);
        });
    }
};
