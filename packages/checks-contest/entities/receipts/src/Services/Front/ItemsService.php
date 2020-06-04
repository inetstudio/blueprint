<?php

namespace Packages\ReceiptsContest\Receipts\Services\Front;

use InetStudio\ReceiptsContest\Receipts\Services\Front\ItemsService as PackageItemsService;

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
