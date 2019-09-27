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
        'comments' => [
            'relationship' => 'hasMany',
            'model' => 'InetStudio\CommentsPackage\Comments\Models\CommentModel',
            'params' => [
                'user_id',
                'id'
            ],
        ],
        'feedback' => [
            'relationship' => 'hasMany',
            'model' => 'InetStudio\FeedbackPackage\Feedback\Models\FeedbackModel',
            'params' => [
                'user_id',
                'id'
            ],
        ],
        'subscription' => [
            'relationship' => 'hasOne',
            'model' => 'InetStudio\Subscription\Models\SubscriptionModel',
            'params' => [
                'user_id',
                'id'
            ],
        ],
    ],
];
