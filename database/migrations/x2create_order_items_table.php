<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();

            // Snapshot sản phẩm tại thời điểm mua
            $table->string('product_name');
            $table->string('sku')->nullable();

            // 🚗 Thuộc tính xe tại thời điểm mua
            $table->string('color')->nullable();
            $table->string('version')->nullable(); // bản tiêu chuẩn / cao cấp

            $table->unsignedInteger('quantity')->default(1);

            // Giá tại thời điểm mua
            $table->decimal('price', 12, 2);
            $table->decimal('line_total', 12, 2);

            $table->timestamps();

            $table->index(['order_id','product_id']);
        });
    }
    public function down(): void {
        Schema::dropIfExists('order_items');
    }
};
