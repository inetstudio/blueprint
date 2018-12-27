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
        /* Relationship example
        'comments' => [
            'relationship' => 'hasMany',
            'model' => 'InetStudio\Comments\Models\CommentModel',
            'params' => [
                'user_id',
                'id'
            ],
        ],
        */
    ],
];
