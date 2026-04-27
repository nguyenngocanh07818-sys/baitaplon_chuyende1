<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->foreignId('brand_id')->nullable()->constrained()->nullOnDelete();

            // Thông tin cơ bản
            $table->string('name'); // Honda Vision 2024
            $table->string('slug')->unique();
            $table->string('sku')->unique();
            $table->longText('description')->nullable();

            // =========================
            // 🚗 THUỘC TÍNH XE
            // =========================
            $table->unsignedInteger('engine_capacity')->nullable(); // cc (125, 150...)
            $table->enum('fuel_type', ['gasoline','electric'])->default('gasoline');
            $table->enum('transmission', ['manual','automatic'])->nullable();
            $table->string('power')->nullable(); // ví dụ: 11 HP
            $table->unsignedInteger('weight')->nullable(); // kg
            $table->string('color')->nullable();

            // =========================
            // 💰 GIÁ
            // =========================
            $table->decimal('price', 12, 2);
            $table->decimal('sale_price', 12, 2)->nullable();

            // =========================
            // 🖼️ HIỂN THỊ
            // =========================
            $table->string('thumbnail')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->enum('status', ['draft','active','hidden'])->default('active');

            $table->timestamps();

            $table->index(['category_id','brand_id','status']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('products');
    }
};