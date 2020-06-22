<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateReceiptsContestPrizesTables.
 */
class CreateReceiptsContestPrizesTables extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('receipts_contest_prizes', function (Blueprint $table) {
            $table->id();
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
        Schema::drop('receipts_contest_prizes');
    }
}
