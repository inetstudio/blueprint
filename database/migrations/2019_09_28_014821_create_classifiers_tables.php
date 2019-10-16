<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateClassifiersTables.
 */
class CreateClassifiersTables extends Migration
{
    /**
    * Run the migrations.
    */
    public function up()
    {
        Schema::create('classifierables', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('entry_model_id')->unsigned();
            $table->morphs('classifierable');
            $table->timestamps();

            $table->foreign('entry_model_id')->references('id')->on('classifiers_entries')->onDelete('cascade');
        });
    }

    /**
    * Reverse the migrations.
    */
    public function down()
    {
        Schema::drop('classifierables');
    }
}
