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
        Schema::create('devices', function (Blueprint $table) {
            $table->increments('deviceID')->comment("設備 id");
            $table->string('name', 100)->comment("名字：車牌、辦公室號碼...");
            $table->string('type', 100)->comment("類型：車型、空間類型.../說明");
            $table->integer('staffID')->unsigned()->comment("提供者/保管人的員工 id");
            $table->foreign('staffID')->references('staffID')->on('staffs');
            $table->string('storage_location', 100)->comment("擺放地點");
            $table->integer('price')->comment("費用");
            $table->integer('attributeID')->unsigned()->comment("屬性 id");
            $table->foreign('attributeID')->references('attributeID')->on('deviceAttributes');
            $table->integer('display')->comment("設備狀態(0→可見，1→隱藏，2→已刪除)");
            $table->integer('approved')->comment("審核狀態(0→未審核，1→審核成功)");
            $table->integer('application_id')->comment("申請人員員工 id");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('devices');
    }
};
