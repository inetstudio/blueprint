<?php

namespace Packages\ReceiptsContest\Receipts\Console\Commands;

use Illuminate\Support\Str;
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

    protected function moderate(): void
    {
        $items = $this->getItems();

        $preliminarilyApprovedStatus = $this->statusesService
            ->getModel()
            ->where('alias', '=', 'preliminarily_approved')
            ->first();

        $rejectedStatus = $this->statusesService
            ->getModel()
            ->where('alias', '=', 'rejected')
            ->first();

        if (! $preliminarilyApprovedStatus || ! $rejectedStatus) {
            return;
        }

        foreach ($items as $item) {
            if (! $item->created_at->greaterThanOrEqualTo($this->contestStartDate)) {
                $this->moderateItem(
                    $item,
                    $rejectedStatus,
                    [
                        'statusReason' => 'Загрузка до начала конкурса',
                    ]
                );

                continue;
            }

            if ($item->created_at->greaterThanOrEqualTo($this->contestEndDate)) {
                $this->moderateItem(
                    $item,
                    $rejectedStatus,
                    [
                        'statusReason' => 'Загрузка после окончания конкурса',
                    ]
                );

                continue;
            }

            $receipt = $item->fnsReceipt;

            if (! $receipt) {
                continue;
            }

            $receiptDate = Carbon::parse($receipt['data']['content']['dateTime']);

            if (! $receiptDate->greaterThanOrEqualTo($this->contestStartDate)) {
                $this->moderateItem(
                    $item,
                    $rejectedStatus,
                    [
                        'statusReason' => 'Дата покупки не соответствует срокам проведения акции',
                    ]
                );

                continue;
            }

            if ($receiptDate->greaterThanOrEqualTo($this->contestEndDate)) {
                $this->moderateItem(
                    $item,
                    $rejectedStatus,
                    [
                        'statusReason' => 'Дата покупки не соответствует срокам проведения акции',
                    ]
                );

                continue;
            }

            $hasProduct = false;

            foreach ($receipt['data']['content']['items'] ?? [] as $productItem) {
                if ($this->checkReceiptProduct($productItem)) {
                    $hasProduct = true;
                }
            }

            if ($hasProduct) {
                $this->moderateItem($item, $preliminarilyApprovedStatus);

                continue;
            }
        }
    }

    protected function checkQrDuplicates(): void
    {
        $items = $this->receiptsService->getModel()->all();

        $rejectedStatus = $this->statusesService
            ->getModel()
            ->where('alias', 'rejected')
            ->first();

        $bar = $this->output->createProgressBar(count($items));

        $codes = [];

        foreach ($items as $item) {
            $receiptCodes = $item->getJSONData('receipt_data', 'codes', []);

            if (empty($receiptCodes) || $item['status_id'] === $rejectedStatus['id']) {
                continue;
            }

            foreach ($receiptCodes as $receiptCode) {
                if (($receiptCode['type'] ?? '') === 'QR_CODE') {
                    $codeValue = trim($receiptCode['value'] ?? '');

                    if (! $codeValue || Str::startsWith($codeValue, 'http')) {
                        continue;
                    }

                    if (! $item->hasJSONData('receipt_data', 'duplicate') && isset($codes[$codeValue])) {
                        $this->moderateItem(
                            $item,
                            $rejectedStatus,
                            [
                                'statusReason' => 'Дубликат',
                                'duplicate' => true
                            ]
                        );
                    } else {
                        $codes[$codeValue] = $item->id;
                    }
                }
            }

            $bar->advance();
        }

        $bar->finish();
    }

    protected function checkReceiptProduct(array $product): bool
    {
        return false;
    }
}
