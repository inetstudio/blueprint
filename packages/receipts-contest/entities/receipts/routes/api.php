<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(
    [
        'prefix' => 'api',
        'middleware' => ['api'],
    ],
    function () {
        Route::group(
            [
                'namespace' => '\InetStudio\ReceiptsContest\Receipts\Contracts\Http\Controllers\Back',
                'prefix' => 'module/receipts-contest',
            ],
            function () {
                Route::get('receipts/export', 'ExportControllerContract@exportItems')->name('api.receipts-contest.receipts.export');
                Route::get('receipts/export/full', 'ExportControllerContract@exportFullItems')->name('api.receipts-contest.receipts.export.full');
            }
        );
    }
);
