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
        Schema::create('core_search_history', function (Blueprint $table) {
            $table->id();
            $table->string('keyword');
            $table->string('user_ip')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('platform')->nullable()->comment('ios, android, web');
            $table->boolean('is_operator')->default(0);
            $table->boolean('from_b2b')->default(0);
            $table->string('device_type')->nullable();
            $table->string('browser')->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();

            $table->index('keyword');
            $table->index('created_at');
            $table->index('platform');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('core_search_history');
    }
};
