<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateChecksContestPrizesTables.
 */
class CreateChecksContestPrizesTables extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('checks_contest_prizes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('alias');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::drop('checks_contest_prizes');
    }
}
