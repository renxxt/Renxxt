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
            $table->string('name', 100)->unique()->comment("名字：車牌、辦公室號碼...");
            $table->string('type', 100)->comment("類型：車型、空間類型.../說明");
            $table->integer('userID')->unsigned()->comment("提供者/保管人的員工 id");
            $table->string('storage_location', 100)->comment("擺放地點");
            $table->integer('price')->comment("費用");
            $table->integer('attributeID')->unsigned()->comment("屬性 id");
            $table->integer('display')->comment("設備狀態(0→可見，1→隱藏，2→已刪除)");
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'))->comment("創建時間");
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'))->comment("更新時間");

            $table->foreign('userID')->references('userID')->on('users');
            $table->foreign('attributeID')->references('attributeID')->on('deviceAttributes');
            $table->index([
                'userID',
                'attributeID'
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
        Schema::dropIfExists('devices');
    }
};
