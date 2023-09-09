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
        Schema::create('users', function (Blueprint $table) {
            $table->increments('userID')->comment("使用者 id");
            $table->string('uid', 10)->unique()->comment("工號(帳號)");
            $table->string('name', 200)->comment("員工姓名");
            $table->integer('departmentID')->unsigned()->comment("部門 id");
            $table->string('email', 120)->comment("信箱");
            $table->string('phonenumber', 10)->comment("電話");
            $table->string('password', 100)->comment("密碼");
            $table->integer('positionID')->unsigned()->comment("職稱 id");
            $table->integer('superiorID')->unsigned()->nullable()->comment("上級 id");
            $table->integer('state')->default(0)->comment("狀態(0→存在，1→已刪除)");

            $table->foreign('departmentID')->references('departmentID')->on('departments');
            $table->foreign('positionID')->references('positionID')->on('positions');
            $table->foreign('superiorID')->references('userID')->on('users');
            $table->index([
                'departmentID',
                'positionID',
                'superiorID'
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
        Schema::dropIfExists('users');
    }
};
