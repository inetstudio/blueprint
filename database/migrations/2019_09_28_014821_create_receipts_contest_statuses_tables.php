<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateReceiptsContestStatusesTables.
 */
class CreateReceiptsContestStatusesTables extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('receipts_contest_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('alias');
            $table->text('description')->nullable();
            $table->string('color_class')->default('default');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('receipts_contest_statuses');
    }
}
