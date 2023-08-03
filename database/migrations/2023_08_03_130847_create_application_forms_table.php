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
        Schema::create('applicationForms', function (Blueprint $table) {
            $table->increments('applicationID')->comment("id");
            $table->integer('userID')->unsigned()->comment("使用者 id");
            $table->integer('deviceID')->unsigned()->comment("設備 id");
            $table->integer('companion')->comment("是否有同伴(0→否，1→有)");
            $table->timestamp('application_time')->comment("預借申請時間");
            $table->datetime('estimated_pickup_time')->comment("預計使用開始時間");
            $table->datetime('estimated_return_time')->comment("預計使用結束時間");
            $table->string('target', 100)->comment("使用目的");

            $table->foreign('userID')->references('userID')->on('users');
            $table->foreign('deviceID')->references('deviceID')->on('devices');
            $table->index([
                'userID',
                'deviceID'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('applicationForms');
    }
};
