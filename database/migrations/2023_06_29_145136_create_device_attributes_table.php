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
        Schema::create('deviceAttributes', function (Blueprint $table) {
            $table->increments('attributeID')->comment("屬性 id");
            $table->string('name', 100)->comment("屬性名字");
            $table->integer('display')->comment("屬性狀態(0→可見，1→隱藏，2→已刪除)");
            $table->integer('approved_layers')->comment("批准層數");
            $table->integer('approved_level')->comment("批准層級");
            $table->integer('picture')->comment("是否需要拍照(0→否，1→事前，2→事後，3→事前事後都需要)");
            $table->integer('picture_amount')->nullable()->comment("拍照數量");
            $table->integer('companion_number')->comment("同伴同行人數");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('deviceAttributes');
    }
};
