<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('applicationDetails', function (Blueprint $table) {
            $table->integer('applicationID')->unsigned()->comment("id");
            $table->timestamp('pickup_time')->comment("取用時間");
            $table->datetime('return_time')->nullable()->comment("歸還時間");

            $table->foreign('applicationID')->references('applicationID')->on('applicationForms');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('applicationDetails');
    }
};
