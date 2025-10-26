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
        Schema::table('bravo_bookings', function (Blueprint $table) {
            $table->string('confirmation_method')->nullable()->after('confirm_type')->comment('Method used for confirmation: whatsapp, email');
            $table->timestamp('confirmed_at')->nullable()->after('confirmation_method')->comment('When the order was confirmed');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bravo_bookings', function (Blueprint $table) {
            $table->dropColumn(['confirmation_method', 'confirmed_at']);
        });
    }
};
