<?php

namespace Packages\ChecksContest\Checks\Console\Commands;

use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Contracts\Container\BindingResolutionException;
use InetStudio\ChecksContest\Checks\Contracts\Models\CheckModelContract;
use InetStudio\ChecksContest\Statuses\Contracts\Models\StatusModelContract;
use InetStudio\ChecksContest\Checks\Console\Commands\ModerateChecksCommand as PackageModerateChecksCommand;

/**
 * Class ModerateChecksCommand.
 */
class ModerateChecksCommand extends PackageModerateChecksCommand
{
    /**
     * Модерируем чеки.
     *
     * @param  Collection  $items
     *
     * @throws BindingResolutionException
     */
    protected function moderate(Collection $items): void
    {
        $statusesService = app()->make('InetStudio\ChecksContest\Statuses\Contracts\Services\Back\ItemsServiceContract');

        $preliminarilyApprovedStatus = $statusesService->getModel()->where('alias', '=', 'preliminarily_approved')->first();

        if (! $preliminarilyApprovedStatus) {
            return;
        }

        $contestStart = Carbon::createFromDate(2019, 10, 31);

        foreach ($items as  $item) {
            $receipt = $item->fnsReceipt;

            if (! $receipt) {
                continue;
            }

            $receiptDate = Carbon::parse($receipt['receipt']['document']['receipt']['dateTime']);

            if (! $receiptDate->greaterThanOrEqualTo($contestStart)) {
                continue;
            }

            if (isset($receipt['receipt']['document']['receipt']['user']) && (mb_strpos(mb_strtolower($receipt['receipt']['document']['receipt']['user']), 'агроторг') !== false || mb_strpos(mb_strtolower($receipt['receipt']['document']['receipt']['user']), 'агроаспект') !== false || mb_strpos(mb_strtolower($receipt['receipt']['document']['receipt']['user']), 'агроаспеkт'))) {
                $hasProduct = false;

                foreach ($receipt['receipt']['document']['receipt']['items'] ?? [] as $productItem) {
                    if (
                        (mb_strpos(mb_strtolower($productItem['name']), '3418507') !== false) ||
                        (mb_strpos(mb_strtolower($productItem['name']), '3024380') !== false) ||
                        (mb_strpos(mb_strtolower($productItem['name']), '55955') !== false) ||
                        (mb_strpos(mb_strtolower($productItem['name']), '55933') !== false) ||
                        (mb_strpos(mb_strtolower($productItem['name']), '16477') !== false) ||
                        (mb_strpos(mb_strtolower($productItem['name']), '3411814') !== false) ||
                        (mb_strpos(mb_strtolower($productItem['name']), '3411815') !== false)
                    ) {
                        $hasProduct = true;
                    }
                }

                if ($hasProduct) {
                    $this->moderateCheck($item, $preliminarilyApprovedStatus);

                    continue;
                }
            }
        }
    }

    /**
     * Переводим чек на нужный статус.
     *
     * @param  CheckModelContract  $item
     * @param  StatusModelContract  $status
     */
    protected function moderateCheck(CheckModelContract $item, StatusModelContract $status)
    {
        $item->status_id = $status->id;
        $item->save();
    }
}
