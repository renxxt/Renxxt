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
        Schema::create('options', function (Blueprint $table) {
            $table->increments('optionID')->comment("選項 id");
            $table->string('option', 100)->comment("選項");
            $table->integer('questionID')->unsigned()->comment("問題 id");
            $table->integer('sort')->comment("排序");
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'))->comment("創建時間");
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'))->comment("更新時間");

            $table->foreign('questionID')->references('questionID')->on('questions');
            $table->index('questionID');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('options');
    }
};
