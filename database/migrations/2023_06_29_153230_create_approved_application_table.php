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
        Schema::create('approvedApplication', function (Blueprint $table) {
            $table->integer('applicationID')->unsigned()->comment("id");
            $table->foreign('applicationID')->references('applicationID')->on('applicationForms');
            $table->integer('approved_staffID')->unsigned()->comment("批准主管的員工 id");
            $table->foreign('approved_staffID')->references('staffID')->on('staffs');
            $table->timestamp('approved_time')->comment("批准時間");
            $table->primary(['applicationID', 'approved_staffID']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('approvedApplication');
    }
};
