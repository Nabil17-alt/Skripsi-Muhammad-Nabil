<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('tb_payments', function (Blueprint $table) {
            $table->unsignedTinyInteger('installment_tenor')->nullable()->after('amount');
            $table->decimal('installment_interest_fee', 15, 2)->default(0)->after('installment_tenor');
            $table->decimal('installment_monthly_amount', 15, 2)->default(0)->after('installment_interest_fee');
        });
    }

    public function down(): void
    {
        Schema::table('tb_payments', function (Blueprint $table) {
            $table->dropColumn([
                'installment_tenor',
                'installment_interest_fee',
                'installment_monthly_amount',
            ]);
        });
    }
};
