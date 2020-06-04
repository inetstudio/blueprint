<?php

namespace Packages\ReceiptsContest\Receipts\Console\Commands;

use Illuminate\Support\Carbon;
use InetStudio\ReceiptsContest\Receipts\Console\Commands\ModerateCommand as PackageModerateCommand;
use InetStudio\ReceiptsContest\Receipts\Contracts\Services\Front\ItemsServiceContract as ReceiptsServiceContract;
use InetStudio\ReceiptsContest\Statuses\Contracts\Services\Back\ItemsServiceContract as StatusesServiceContract;

class ModerateCommand extends PackageModerateCommand
{
    public function __construct(ReceiptsServiceContract $receiptsService, StatusesServiceContract $statusesService)
    {
        parent::__construct($receiptsService, $statusesService);

        $this->contestStartDate = Carbon::createFromDate(2020, 5, 1, 'Europe/Moscow')->setTime(0, 0, 0);
        $this->contestEndDate = Carbon::createFromDate(2020, 6, 22, 'Europe/Moscow')->setTime(0, 0, 0);
    }

    protected function checkReceiptProduct(array $product): bool
    {
        return (mb_strpos(mb_strtolower($product['name']), 'cast') !== false && mb_strpos(mb_strtolower($product['name']), 'краск') !== false) ||
            (mb_strpos(mb_strtolower($product['name']), 'каст') !== false && mb_strpos(mb_strtolower($product['name']), 'краск') !== false) ||
            (mb_strpos(mb_strtolower($product['name']), 'casting') !== false && mb_strpos(mb_strtolower($product['name']), 'д/в') !== false) ||
            (mb_strpos(mb_strtolower($product['name']), 'casting') !== false && mb_strpos(mb_strtolower($product['name']), 'крас') !== false);
    }
}
