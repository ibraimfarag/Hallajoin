<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBravoReview extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bravo_review', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->integer('object_id')->nullable();
            $table->string('object_model',255)->nullable();
            $table->string('title', 255)->nullable();
            $table->text('content')->nullable();
            $table->integer('rate_number')->nullable();
            $table->string('author_ip',100)->nullable();
            $table->boolean('future')->default(false); // Add the boolean 'future' field

            $table->string('status',50)->nullable();
            $table->dateTime('publish_date')->nullable();
            $table->bigInteger('create_user')->nullable();
            $table->bigInteger('update_user')->nullable();
            $table->softDeletes();

            //Languages
            $table->string('lang',10)->nullable();

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
        Schema::dropIfExists('bravo_review');
    }
}
