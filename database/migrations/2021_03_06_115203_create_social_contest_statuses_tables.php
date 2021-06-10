<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSocialContestStatusesTables extends Migration
{
    public function up()
    {
        Schema::create('social_contest_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('alias');
            $table->text('description')->nullable();
            $table->string('color_class')->default('default');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('social_contest_statuses');
    }
}
