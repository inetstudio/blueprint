<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateChecksContestStatusesTables.
 */
class CreateChecksContestStatusesTables extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('checks_contest_statuses', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('alias');
            $table->text('description')->nullable();
            $table->string('color_class')->default('default');
            $table->boolean('fill_reason')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('checks_contest_statuses');
    }
}
