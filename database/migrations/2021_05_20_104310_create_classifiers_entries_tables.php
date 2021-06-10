<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateClassifiersEntriesTables.
 */
class CreateClassifiersEntriesTables extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('classifiers_entries', function (Blueprint $table) {
            $table->increments('id');
            $table->string('value');
            $table->string('alias')->default('');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::drop('classifiers_entries');
    }
}
