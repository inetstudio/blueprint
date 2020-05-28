<?php

namespace Packages\ChecksContest\Checks\Services\Front;

use InetStudio\ChecksContest\Checks\Services\Front\ItemsService as PackageItemsService;

/**
 * Class ItemsService.
 */
final class ItemsService extends PackageItemsService
{
    /**
     * @var array
     */
    public $stages = [
        '08.10.19' => [
            [
                'prize' => '',
                'start' => '01.10.19',
                'end' => '01.10.19',
                'confirmed' => 0,
                'count' => 9999,
            ],
        ]
    ];
}
