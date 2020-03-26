<?php

return [

    /*
     * Настройки изображений.
     */

    'images' => [
        'quality' => 75,
        'conversions' => [
            'user' => [
                'image' => [
                    'default' => [
                        [
                            'name' => 'image_default',
                            'size' => [
                                'width' => 256,
                                'height' => 256,
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    /*
     * Настройки связей.
     */

    'relationships' => [
        'feedback' => [
            'relationship' => 'hasMany',
            'model' => 'InetStudio\FeedbackPackage\Feedback\Models\FeedbackModel',
            'params' => [
                'user_id',
                'id'
            ],
        ],
    ],
];
