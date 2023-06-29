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
        Schema::create('stagedapplicationCompanion', function (Blueprint $table) {
            $table->integer('applicationID')->unsigned()->comment("id");
            $table->foreign('applicationID')->references('applicationID')->on('stagedapplicationForms');
            $table->integer('staffID')->unsigned()->comment("員工 id");
            $table->foreign('staffID')->references('staffID')->on('staffs');
            $table->primary(['applicationID', 'staffID']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stagedapplicationCompanion');
    }
};
