<?php

use Illuminate\Support\Facades\Route;

Route::group(
    [
        'prefix' => 'api',
        'middleware' => ['api', 'auth:api', 'role:inetstudio|btl'],
    ],
    function () {
        Route::group(
            [
                'namespace' => '\InetStudio\ReceiptsContest\Receipts\Contracts\Http\Controllers\Back',
                'prefix' => 'module/receipts-contest',
            ],
            function () {
                Route::get('receipts/export/default', 'ExportControllerContract@exportItems')
                    ->name('api.receipts-contest.receipts.export.default');

                Route::get('receipts/export/full', 'ExportControllerContract@exportItemsFull')
                    ->name('api.receipts-contest.receipts.export.full');
            }
        );
    }
);
