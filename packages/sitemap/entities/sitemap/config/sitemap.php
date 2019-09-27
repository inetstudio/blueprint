<?php

return [

    'maps' => [
        'sitemap' => [
            'options' => [
                'format' => 'xml',
                'style' => 'sitemap',
                'limit' => 0,
            ],
            'sources' => [
                'pages' => 'InetStudio\PagesPackage\Pages\Contracts\Services\Front\SitemapServiceContract@getItems',
            ],
            'except' => [
                'page/index',
            ],
        ],
    ],

];
