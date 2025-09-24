<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cart_id')->constrained()->onDelete('cascade');
            $table->string('service_type'); // 'tour', 'hotel', 'car', 'space', 'boat', 'event', 'flight'
            $table->unsignedBigInteger('service_id'); // ID of the actual service
            $table->integer('quantity')->default(1);
            $table->decimal('price', 10, 2);
            $table->decimal('total_price', 10, 2);
            $table->json('booking_data')->nullable(); // تفاصيل الحجز مثل التواريخ، الضيوف
            $table->timestamps();

            $table->index(['cart_id']);
            $table->index(['service_type', 'service_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_items');
    }
};
