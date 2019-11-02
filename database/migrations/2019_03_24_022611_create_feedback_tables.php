<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateFeedbackTables.
 */
class CreateFeedbackTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('feedback', function (Blueprint $table) {
            $table->increments('id');
            $table->nullableMorphs('feedbackable');
            $table->boolean('is_read')->default(0);
            $table->string('user_id')->default(0);
            $table->string('name');
            $table->string('email');
            $table->text('message')->nullable();
            $table->text('response')->nullable();
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
        Schema::drop('feedback');
    }
}
