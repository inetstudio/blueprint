<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Facades\Route;

Route::group(
    [
        'namespace' => '\App\Entities\PagesPackage\Pages\Http\Controllers\Front'
    ],
    function () {
        // Regular Requests
        Route::get('/', 'ItemsController@getIndex')->name('front.pages.index.get');
    }
);
