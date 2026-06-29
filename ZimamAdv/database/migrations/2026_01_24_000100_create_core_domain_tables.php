<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Metode pembayaran (QRIS, E-Wallet, Transfer Bank, Tunai, Cicilan)
        Schema::create('tb_payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // QRIS, E-Wallet, Transfer Bank, Tunai, Cicilan
            $table->string('type')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Rekening bank untuk metode Transfer Bank
        Schema::create('tb_bank_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('bank_name');
            $table->string('account_number');
            $table->string('account_holder');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Alamat pelanggan (bisa lebih dari satu per user)
        Schema::create('tb_addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('tb_users');
            $table->string('label')->nullable();
            $table->string('recipient_name');
            $table->string('phone');
            $table->text('full_address');
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });

        // Produk percetakan
        Schema::create('tb_products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('category')->nullable();
            $table->text('description')->nullable();
            $table->decimal('base_price', 15, 2)->default(0);
            $table->integer('lead_time_days')->default(1); // estimasi pengerjaan
            $table->boolean('allow_custom_design')->default(true);
            $table->boolean('allow_design_service')->default(true);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Gambar produk
        Schema::create('tb_product_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('tb_products')->onDelete('cascade');
            $table->string('image_path');
            $table->boolean('is_primary')->default(false);
            $table->timestamps();
        });

        // Varian produk (ukuran / bahan)
        Schema::create('tb_product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('tb_products')->onDelete('cascade');
            $table->string('size')->nullable();
            $table->string('material')->nullable();
            $table->decimal('price', 15, 2)->default(0);
            $table->timestamps();
        });

        // Promo / diskon (opsional)
        Schema::create('tb_promos', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('description')->nullable();
            $table->enum('discount_type', ['percent', 'nominal']);
            $table->decimal('discount_value', 15, 2);
            $table->dateTime('start_at')->nullable();
            $table->dateTime('end_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Pesanan (order)
        Schema::create('tb_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('tb_users');
            $table->string('order_number')->unique();
            $table->foreignId('shipping_address_id')->nullable()->constrained('tb_addresses');
            $table->foreignId('promo_id')->nullable()->constrained('tb_promos');
            $table->decimal('subtotal_amount', 15, 2);
            $table->decimal('discount_amount', 15, 2)->default(0);
            $table->decimal('shipping_fee', 15, 2)->default(0);
            $table->decimal('grand_total', 15, 2);
            $table->decimal('shipping_distance_km', 8, 2)->nullable();
            $table->string('production_status')->default('menunggu_pembayaran'); // menunggu_pembayaran, diproses, desain, revisi, cetak, selesai
            $table->string('payment_status')->default('pending'); // pending, lunas, gagal
            $table->string('delivery_method')->nullable(); // antar / ambil_di_toko
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // Item dalam pesanan
        Schema::create('tb_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('tb_orders')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('tb_products');
            $table->foreignId('product_variant_id')->nullable()->constrained('tb_product_variants');
            $table->integer('quantity');
            $table->decimal('unit_price', 15, 2);
            $table->decimal('design_service_fee', 15, 2)->default(0);
            $table->text('notes')->nullable();
            $table->string('design_file_path')->nullable();
            $table->timestamps();
        });

        // Pembayaran
        Schema::create('tb_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('tb_orders')->onDelete('cascade');
            $table->foreignId('payment_method_id')->constrained('tb_payment_methods');
            $table->foreignId('bank_account_id')->nullable()->constrained('tb_bank_accounts');
            $table->decimal('amount', 15, 2);
            $table->string('status')->default('pending'); // pending, lunas, gagal, refund
            $table->string('transaction_id_gateway')->nullable();
            $table->string('reference')->nullable();
            $table->string('payment_proof_path')->nullable();
            $table->text('raw_callback_log')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });

        // Chat (room) antara customer dan admin
        Schema::create('tb_chats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('tb_users'); // pemilik chat (customer)
            $table->foreignId('order_id')->nullable()->constrained('tb_orders');
            $table->timestamps();
        });

        // Pesan dalam chat
        Schema::create('tb_chat_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chat_id')->constrained('tb_chats')->onDelete('cascade');
            $table->enum('sender_type', ['admin', 'customer']);
            $table->unsignedBigInteger('sender_id'); // id user admin/customer
            $table->text('message')->nullable();
            $table->string('file_path')->nullable();
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_chat_messages');
        Schema::dropIfExists('tb_chats');
        Schema::dropIfExists('tb_payments');
        Schema::dropIfExists('tb_order_items');
        Schema::dropIfExists('tb_orders');
        Schema::dropIfExists('tb_promos');
        Schema::dropIfExists('tb_product_variants');
        Schema::dropIfExists('tb_product_images');
        Schema::dropIfExists('tb_products');
        Schema::dropIfExists('tb_addresses');
        Schema::dropIfExists('tb_bank_accounts');
        Schema::dropIfExists('tb_payment_methods');
    }
};
