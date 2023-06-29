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
        Schema::create('stagedapplicationForms', function (Blueprint $table) {
            $table->increments('applicationID')->comment("id");
            $table->integer('staffID')->unsigned()->comment("員工 id");
            $table->foreign('staffID')->references('staffID')->on('staffs');
            $table->integer('deviceID')->unsigned()->comment("設備 id");
            $table->foreign('deviceID')->references('deviceID')->on('devices');
            $table->integer('companion')->comment("是否有同伴(0→否，1→有)");
            $table->datetime('estimated_pickup_time')->comment("預計使用開始時間");
            $table->datetime('estimated_return_time')->comment("預計使用結束時間");
            $table->string('target', 100)->comment("使用目的");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stagedapplicationForms');
    }
};
