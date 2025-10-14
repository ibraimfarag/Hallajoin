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
            $table->bigInteger('salesman_id')->default(0)->change();
        });

        // Update existing NULL values to 0 (Not Assigned)
        DB::table('bravo_bookings')->whereNull('salesman_id')->update(['salesman_id' => 0]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bravo_bookings', function (Blueprint $table) {
            $table->bigInteger('salesman_id')->nullable()->change();
        });
    }
};
