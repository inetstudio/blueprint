<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateACLActivationsTables extends Migration
{
    public function up()
    {
       Schema::create('users_activations', function (Blueprint $table) {
           $table->integer('user_id')->unsigned()->index();
           $table->string('token')->index();
           $table->timestamp('created_at');
       });

       if (Schema::hasTable('users')) {
           Schema::table('users', function (Blueprint $table) {
                $table->boolean('activated')->default(0)->after('id');
           });
       }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('activated');
        });

        Schema::drop('users_activations');
    }
}
