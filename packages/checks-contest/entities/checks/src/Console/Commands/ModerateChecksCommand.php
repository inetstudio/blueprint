<?php

namespace Packages\ChecksContest\Checks\Console\Commands;

use Illuminate\Support\Carbon;
use InetStudio\ChecksContest\Checks\Console\Commands\ModerateChecksCommand as PackageModerateChecksCommand;
use InetStudio\ChecksContest\Checks\Contracts\Services\Front\ItemsServiceContract as ReceiptsServiceContract;
use InetStudio\ChecksContest\Statuses\Contracts\Services\Back\ItemsServiceContract as StatusesServiceContract;

/**
 * Class ModerateChecksCommand.
 */
class ModerateChecksCommand extends PackageModerateChecksCommand
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
