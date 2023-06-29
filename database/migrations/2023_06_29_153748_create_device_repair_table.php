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
        Schema::create('deviceRepair', function (Blueprint $table) {
            $table->integer('repairID')->comment("id");
            $table->integer('deviceID')->unsigned()->comment("設備 id");
            $table->foreign('deviceID')->references('deviceID')->on('devices');
            $table->string('direction', 100)->comment("說明");
            $table->timestamp('repair_time')->comment("報修時間");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('deviceRepair');
    }
};
