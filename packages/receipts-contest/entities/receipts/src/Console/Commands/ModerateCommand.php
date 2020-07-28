<?php

namespace Packages\ReceiptsContest\Receipts\Console\Commands;

use Illuminate\Support\Carbon;
use InetStudio\ReceiptsContest\Receipts\Contracts\Services\Back\ModerateServiceContract;
use InetStudio\ReceiptsContest\Receipts\Console\Commands\ModerateCommand as PackageModerateCommand;
use InetStudio\ReceiptsContest\Receipts\Contracts\Services\Back\ItemsServiceContract as ReceiptsServiceContract;
use InetStudio\ReceiptsContest\Statuses\Contracts\Services\Back\ItemsServiceContract as StatusesServiceContract;

class ModerateCommand extends PackageModerateCommand
{
    public function __construct(ReceiptsServiceContract $receiptsService, StatusesServiceContract $statusesService, ModerateServiceContract $moderateService)
    {
        parent::__construct($receiptsService, $statusesService, $moderateService);

        $this->contestStartDate = Carbon::createFromDate(2020, 6, 10, 'Europe/Moscow')->setTime(0, 0, 0);
        $this->contestEndDate = Carbon::createFromDate(2020, 9, 23, 'Europe/Moscow')->setTime(0, 0, 0);
    }

    protected function checkReceiptProduct(array $product): bool
    {
        return (mb_strpos(mb_strtolower($product['name']), 'garn.') !== false);
    }
}
