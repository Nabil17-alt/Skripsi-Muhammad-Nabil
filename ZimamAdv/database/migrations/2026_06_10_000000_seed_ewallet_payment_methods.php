<?php

use Illuminate\Database\Migrations\Migration;
use App\Models\PaymentMethod;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Removed DANA, OVO, ShopeePay seeding as we use Midtrans Snap container
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Keep payment methods to prevent foreign key errors for existing payments.
    }
};
