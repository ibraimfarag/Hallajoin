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
        Schema::table('bravo_enquiries', function (Blueprint $table) {
            if (! Schema::hasColumn('bravo_enquiries', 'salesman')) {
                $table->unsignedBigInteger('salesman')->nullable()->after('vendor_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bravo_enquiries', function (Blueprint $table) {
            if (Schema::hasColumn('bravo_enquiries', 'salesman')) {
                $table->dropColumn('salesman');
            }
        });
    }
};
