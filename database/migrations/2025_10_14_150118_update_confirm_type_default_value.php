<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('bravo_bookings', function (Blueprint $table) {
            $table->string('confirm_type')->default('pending')->change();
        });

        // Update existing NULL values to 'pending'
        DB::table('bravo_bookings')->whereNull('confirm_type')->update(['confirm_type' => 'pending']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bravo_bookings', function (Blueprint $table) {
            $table->string('confirm_type')->nullable()->change();
        });
    }
};
