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
        Schema::create('cancelApplication', function (Blueprint $table) {
            $table->integer('applicationID')->unsigned()->comment("id");
            $table->string('result', 100)->comment("取消申請原因");
            $table->timestamp('cancel_time')->comment("取消申請時間");

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
        Schema::dropIfExists('cancelApplication');
    }
};
