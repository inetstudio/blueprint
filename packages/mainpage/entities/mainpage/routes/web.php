<?php

use Illuminate\Support\Facades\Route;

Route::group(
    [
        'namespace' => '\Packages\MainPagePackage\MainPage\Http\Controllers\Front',
        'middleware' => ['web'],
    ],
    function () {
        Route::get('/', 'IndexController@getItem')->name('front.index.get');
    }
);
