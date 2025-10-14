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
            $table->string('confirm_type')->default('pending')->after('status');
            $table->bigInteger('salesman_id')->nullable()->after('confirm_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bravo_bookings', function (Blueprint $table) {
            $table->dropColumn(['confirm_type', 'salesman_id']);
        });
    }
};
