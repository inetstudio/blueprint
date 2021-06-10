<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateClassifiersGroupsTables.
 */
class CreateClassifiersGroupsTables extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('classifiers_groups', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('alias');
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('classifiers_groups_entries', function (Blueprint $table) {
            $table->integer('group_model_id');
            $table->integer('entry_model_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('classifiers_groups_entries');
        Schema::dropIfExists('classifiers_groups');
    }
}
