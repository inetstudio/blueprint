<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSocialContestPostsTables extends Migration
{
    public function up()
    {
        Schema::create('social_contest_posts', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->integer('user_id')->default(0);
            $table->morphs('social');
            $table->bigInteger('status_id')->unsigned()->index()->default(0);
            $table->json('search_data')->nullable();
            $table->json('additional_info')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('social_contest_posts_prizes', function (Blueprint $table) {
            $table->integer('post_id')->unsigned()->index();
            $table->integer('prize_id')->unsigned()->index();
            $table->smallInteger('confirmed')->unsigned()->default(0);
            $table->timestamp('date_start')->nullable();
            $table->timestamp('date_end')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('social_contest_posts_prizes');
        Schema::dropIfExists('social_contest_posts');
    }
}
