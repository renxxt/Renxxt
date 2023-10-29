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
        Schema::table('applicationForms', function (Blueprint $table) {
            $table->string('uuid', 100)->comment("時間戳")->after('applicationID');
            $table->datetime('pickup_time')->comment("取用時間")->nullable()->after('estimated_return_time');
            $table->datetime('return_time')->comment("歸還時間")->nullable()->after('pickup_time');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('applicationForms', function (Blueprint $table) {
            $table->dropColumn('uuid');
            $table->dropColumn('pickup_time');
            $table->dropColumn('return_time');
        });
    }
};
