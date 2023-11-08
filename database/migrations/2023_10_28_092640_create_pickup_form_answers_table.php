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
        Schema::create('pickupFormAnswers', function (Blueprint $table) {
            $table->id();
            $table->integer('applicationID')->unsigned()->comment("id");
            $table->integer('questionID')->unsigned()->comment("問題 id");
            $table->integer('selected_optionID')->unsigned()->nullable()->comment("所選選項 id");
            $table->string('answer_text', 100)->nullable()->comment("文字回答");
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'))->comment("創建時間");
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'))->comment("更新時間");

            $table->foreign('applicationID')->references('applicationID')->on('applicationforms');
            $table->foreign('questionID')->references('questionID')->on('questions');
            $table->foreign('selected_optionID')->references('optionID')->on('options');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pickupFormAnswers');
    }
};
