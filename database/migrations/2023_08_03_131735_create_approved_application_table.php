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
            $table->integer('approved_userID')->unsigned()->comment("批准主管的使用者 id");
            $table->timestamp('approved_time')->comment("批准時間");
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'))->comment("創建時間");
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'))->comment("更新時間");

            $table->foreign('applicationID')->references('applicationID')->on('applicationForms');
            $table->foreign('approved_userID')->references('userID')->on('users');
            $table->primary([
                'applicationID',
                'approved_userID'
            ]);
            $table->index('approved_userID');
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
