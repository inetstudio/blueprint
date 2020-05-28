<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateACLProfilesTables extends Migration
{
    public function up()
    {
       Schema::create('users_profiles', function (Blueprint $table) {
           $table->increments('id');
           $table->integer('user_id')->unsigned()->default(0)->index();
           $table->json('additional_info');
           $table->timestamps();
           $table->softDeletes();
       });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('users_profiles');
    }
}
