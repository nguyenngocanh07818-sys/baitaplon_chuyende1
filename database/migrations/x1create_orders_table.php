<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            // User
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();

            // Thông tin khách hàng
            $table->string('customer_name');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();

            // Địa chỉ giao xe
            $table->string('address_line1')->nullable();
            $table->string('address_line2')->nullable();
            $table->string('ward')->nullable();
            $table->string('district')->nullable();
            $table->string('province')->nullable();

            // =========================
            // 🚗 TRẠNG THÁI ĐƠN
            // =========================
            $table->enum('status', [
                'pending','confirmed','deposit_paid','delivered','completed','cancelled'
            ])->default('pending');

            $table->enum('payment_method', ['deposit','full'])->default('deposit');

            // =========================
            // 💰 TIỀN
            // =========================
            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('discount', 12, 2)->default(0);
            $table->decimal('delivery_fee', 12, 2)->default(0);

            $table->decimal('deposit_amount', 12, 2)->default(0);
            $table->decimal('remaining_amount', 12, 2)->default(0);

            $table->decimal('total', 12, 2)->default(0);

            // =========================
            // 📅 THEO DÕI
            // =========================
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('delivered_at')->nullable();

            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['status','payment_method']);
        });
    }
    public function down(): void {
        Schema::dropIfExists('orders');
    }
};