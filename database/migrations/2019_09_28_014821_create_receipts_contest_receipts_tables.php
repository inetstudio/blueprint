<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateReceiptsContestReceiptsTables.
 */
class CreateReceiptsContestReceiptsTables extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('receipts_contest_receipts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('fns_receipt_id')->index()->default(0);
            $table->string('verify_hash')->default('');
            $table->json('receipt_data')->nullable();
            $table->json('additional_info')->nullable();
            $table->string('user_id')->default(0);
            $table->integer('status_id')->unsigned()->index()->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('receipts_contest_receipts_prizes', function (Blueprint $table) {
            $table->integer('receipt_id')->unsigned()->index();
            $table->integer('prize_id')->unsigned()->index();
            $table->smallInteger('confirmed')->unsigned()->default(0);
            $table->timestamp('date_start')->nullable();
            $table->timestamp('date_end')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::drop('receipts_contest_receipts_prizes');
        Schema::drop('receipts_contest_receipts');
    }
}
