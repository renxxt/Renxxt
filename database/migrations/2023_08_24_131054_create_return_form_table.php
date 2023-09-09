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
        Schema::create('returnForm', function (Blueprint $table) {
            $table->integer('attributeID')->unsigned()->comment("屬性 id");
            $table->integer('questionID')->unsigned()->comment("問題 id");
            $table->integer('order')->comment("排序");
            $table->integer('required')->default(0)->comment("必填(0→必填，1→可不填)");

            $table->foreign('attributeID')->references('attributeID')->on('deviceAttributes');
            $table->foreign('questionID')->references('questionID')->on('questions');
            $table->primary([
                'attributeID',
                'questionID'
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
        Schema::dropIfExists('returnForm');
    }
};
