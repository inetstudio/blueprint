<?php

return [

    /*
     * Настройки изображений
     */

    'images' => [
        'quality' => 75,
        'conversions' => [
            'check' => [
                'images' => [
                    'default' => [
                        [
                            'name' => 'admin_index_thumb',
                            'crop' => [
                                'width' => 100,
                                'height' => 100,
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'recognize_barcode_service' => env('RECEIPTS_RECOGNIZE_BARCODE_SERVICE'),
    'fns_service' => env('RECEIPTS_FNS_SERVICE'),
    'services_token' => env('RECEIPTS_SERVICES_TOKEN'),
];
