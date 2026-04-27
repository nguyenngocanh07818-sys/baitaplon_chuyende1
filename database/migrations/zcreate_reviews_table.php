<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->unsignedTinyInteger('rating')->unsigned()->check('rating', 'rating >= 1 AND rating <= 5');
            $table->text('comment')->nullable();
            $table->timestamps();

            // Đảm bảo mỗi user chỉ đánh giá 1 lần cho mỗi sản phẩm trong mỗi đơn hàng
            $table->unique(['user_id', 'order_id', 'product_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};