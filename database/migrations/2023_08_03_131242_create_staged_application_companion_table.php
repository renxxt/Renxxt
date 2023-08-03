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
        Schema::create('stagedApplicationCompanion', function (Blueprint $table) {
            $table->integer('applicationID')->unsigned()->comment("id");
            $table->integer('userID')->unsigned()->comment("使用者 id");

            $table->foreign('applicationID')->references('applicationID')->on('stagedApplicationForms');
            $table->foreign('userID')->references('userID')->on('users');
            $table->primary([
                'applicationID',
                'userID'
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
        Schema::dropIfExists('stagedApplicationCompanion');
    }
};
