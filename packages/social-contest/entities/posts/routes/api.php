<?php

use Illuminate\Support\Facades\Route;

Route::group(
    [
        'prefix' => 'api',
        'middleware' => ['api', 'auth:api', 'role:inetstudio'],
    ],
    function () {
        Route::group(
            [
                'namespace' => '\InetStudio\SocialContest\Posts\Contracts\Http\Controllers\Back',
                'prefix' => 'module/social-contest',
            ],
            function () {
                Route::get('posts/export/default', 'ExportControllerContract@exportItems')
                    ->name('api.social-contest.posts.export.default');
            }
        );
    }
);
