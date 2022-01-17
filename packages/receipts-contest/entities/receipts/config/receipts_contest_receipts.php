<?php

return [

    /*
     * Настройки изображений
     */

    'images' => [
        'quality' => 75,
        'conversions' => [
            'receipt' => [
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

    'mails' => [
        'win' => [
            'bag' => [
                'subject' => 'Поздравляем! Поздравляем! Вы выиграли ежедневный приз – сумку шоппер',
            ],
            'certificate' => [
                'subject' => 'Поздравляем! Вы выиграли еженедельный приз – сертификат',
            ],
            'spa' => [
                'subject' => 'Поздравляем! Вы выиграли главный приз – сертификат',
            ],
        ],
    ],

    'moderation' => [
        'start_date' => null,
        'end_date' => null,
        'sum' => 0,
        'retails' => [
            'title' => 'search',
        ],
    ],
];
