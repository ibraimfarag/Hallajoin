<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoreTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('core_templates', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('title', 255)->nullable();
            $table->longText('content')->nullable();
            $table->integer('type_id')->nullable();

            $table->integer('create_user')->nullable();
            $table->integer('update_user')->nullable();

            // Languages
            $table->bigInteger('origin_id')->nullable();
            $table->string('lang', 10)->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('core_templates');
    }
}
