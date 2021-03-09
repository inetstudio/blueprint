<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateFnsReceiptsTables.
 */
class CreateFnsReceiptsTables extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('fns_receipts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('qr_code', 500)->nullable();
            $table->json('data')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::drop('fns_receipts');
    }
}
