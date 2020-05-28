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
                'namespace' => '\InetStudio\ChecksContest\Checks\Contracts\Http\Controllers\Back',
                'prefix' => 'module/checks-contest',
            ],
            function () {
                Route::get('checks/export', 'ExportControllerContract@exportItems')->name('api.checks-contest.checks.export');
                Route::get('checks/full/export', 'ExportControllerContract@exportFullItems')->name('api.checks-contest.checks.fullexport');
            }
        );
    }
);
