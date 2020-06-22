<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateReceiptsContestProductsTables.
 */
class CreateReceiptsContestProductsTables extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('receipts_contest_products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('fns_receipt_id')->default(0);
            $table->unsignedBigInteger('receipt_id');
            $table->string('name');
            $table->unsignedDecimal('quantity', 9, 3);
            $table->unsignedInteger('price');
            $table->json('product_data')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::drop('receipts_contest_products');
    }
}
