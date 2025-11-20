<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddApplyToToCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (! Schema::hasColumn('bravo_coupons', 'apply_to')) {
            Schema::table('bravo_coupons', function (Blueprint $table) {
                $table->string('apply_to', 50)->nullable()->after('only_for_user');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('bravo_coupons', 'apply_to')) {
            Schema::table('bravo_coupons', function (Blueprint $table) {
                $table->dropColumn('apply_to');
            });
        }
    }
}
