<?php

namespace Packages\ReceiptsContest\Receipts\Services\Front;

use Illuminate\Support\Carbon;
use InetStudio\ReceiptsContest\Receipts\Contracts\Models\ReceiptModelContract;
use InetStudio\ReceiptsContest\Receipts\Services\Front\ItemsService as PackageItemsService;

final class ItemsService extends PackageItemsService
{
    public function __construct(ReceiptModelContract $model)
    {
        parent::__construct($model);

        $prizes = [
            'bag' => [
                'start' => '10.06.20',
                'end' => '22.09.20',
                'next' => '1',
                'draw' => '3',
                'confirmed' => 0,
                'count' => 1,
            ],
            'certificate' => [
                'start' => '10.06.20',
                'end' => '22.09.20',
                'next' => '7',
                'draw' => '3',
                'confirmed' => 0,
                'count' => 1,
            ],
            'spa' => [
                'start' => '10.06.20',
                'end' => '22.09.20',
                'next' => '105',
                'draw' => '3',
                'confirmed' => 0,
                'count' => 5,
            ],
        ];

        foreach ($prizes as $alias => $prize) {
            $start = Carbon::createFromFormat('d.m.y', $prize['start']);
            $end = Carbon::createFromFormat('d.m.y', $prize['end']);

            while ($start->lessThanOrEqualTo($end)) {
                $startPeriod = Carbon::parse($start);
                $endPeriod = Carbon::parse($start)->addDays($prize['next']-1);

                $drawDate = Carbon::parse($endPeriod)->addDays($prize['draw']);

                $this->stages[$drawDate->format('d.m.y')][] = [
                    'prize' => $alias,
                    'start' => $startPeriod->format('d.m.y'),
                    'end' => $endPeriod->format('d.m.y'),
                    'confirmed' => $prize['confirmed'],
                    'count' => $prize['count'],
                ];

                $start->addDays($prize['next']);
            }
        }
    }
}
