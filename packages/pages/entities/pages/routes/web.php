<?php

use Illuminate\Support\Facades\Route;

Route::group(
    [
        'namespace' => '\Packages\PagesPackage\Pages\Http\Controllers\Front',
        'middleware' => ['web'],
    ],
    function () {
        Route::get('/page/{slug}', 'ItemsController@getItem')->name('front.pages.get');
    }
);
