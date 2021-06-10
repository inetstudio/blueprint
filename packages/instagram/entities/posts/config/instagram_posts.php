<?php

return [
    /*
     * Настройки изображений
     */

    'images' => [
        'quality' => 100,
        'conversions' => [
            'post' => [
                'media' => [
                    'default' => [
                        [
                            'name' => 'preview_admin_form',
                            'size' => [
                                'width' => 96,
                                'height' => 96,
                            ],
                        ],
                        [
                            'name' => 'preview_admin_index',
                            'size' => [
                                'width' => 320,
                                'height' => 320,
                            ],
                        ],
                        [
                            'name' => 'preview_gallery',
                            'fit' => [
                                'method' => 'crop',
                                'width' => 91,
                                'height' => 91,
                            ],
                        ],
                    ],
                ],
                'cover' => [
                    'default' => [
                        [
                            'name' => 'cover_admin_form',
                            'size' => [
                                'width' => 96,
                                'height' => 96,
                            ],
                        ],
                        [
                            'name' => 'cover_admin_index',
                            'size' => [
                                'width' => 320,
                                'height' => 320,
                            ],
                        ],
                        [
                            'name' => 'cover_gallery',
                            'fit' => [
                                'method' => 'crop',
                                'width' => 195,
                                'height' => 285,
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
];
