<?php

namespace Packages\ReceiptsContest\Receipts\Console\Commands;

use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Arr;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Contracts\Container\BindingResolutionException;
use InetStudio\ReceiptsContest\Receipts\Console\Commands\AttachFnsReceiptsCommand as PackageAttachFnsReceiptsCommand;

/**
 * Class AttachFnsReceiptsCommand.
 */
class AttachFnsReceiptsCommand extends PackageAttachFnsReceiptsCommand
{
    /**
     * Запуск команды.
     *
     * @throws BindingResolutionException
     *
     * @throws GuzzleException
     */
    public function handle()
    {
        $checksService = app()->make('InetStudio\ReceiptsContest\Receipts\Contracts\Services\Back\ItemsServiceContract');
        $statusesService = app()->make('InetStudio\ReceiptsContest\Statuses\Contracts\Services\Back\ItemsServiceContract');
        $receiptsService = app()->make('InetStudio\Fns\Receipts\Contracts\Services\Back\ItemsServiceContract');

        $status = $statusesService->getDefaultStatus();

        $checks = $checksService->getModel()->where([
            ['status_id', '=', $status->id],
        ])
            ->doesntHave('fnsReceipt')
            ->orderBy('created_at', 'desc')
            ->where('created_at', '>',
                Carbon::now()->subDays(3)->toDateTimeString()
            )
            ->get();

        $client = new Client();

        $bar = $this->output->createProgressBar(count($checks));

        foreach ($checks as $check) {
            $codes = $check->getJSONData('receipt_data', 'codes', []);

            $fnsReceipt = null;
            $products = [];

            foreach ($codes as $code) {
                if (! (($code['type'] ?? '') == 'QR_CODE')) {
                    continue;
                }

                if (! ($code['value'] ?? '')) {
                    continue;
                }

                $response = $client->request(
                    'POST',
                    config('checks_contest_checks.fns_service'),
                    [
                        'headers' => [
                            'Authorization' => 'Bearer '.config('checks_contest_checks.services_token'),
                            'Accept' => 'application/json',
                        ],
                        'form_params' => [
                            'qr_code' => $code['value'],
                        ]
                    ]
                );

                $fnsReceiptData = json_decode($response->getBody()->getContents(), true);

                if (! empty($fnsReceiptData)) {
                    $fnsReceipt = $receiptsService->save($fnsReceiptData, 0);
                }

                if ($fnsReceipt) {
                    $fnsReceiptData = $fnsReceipt->receipt['document']['receipt'];

                    foreach ($fnsReceiptData['items'] ?? [] as $item) {
                        $products[] = [
                            'receipt_id' => $check->id,
                            'fns_receipt_id' => $fnsReceipt->id,
                            'name' => $item['name'],
                            'quantity' => $item['quantity'],
                            'price' => $item['price'],
                            'product_data' => $item,
                        ];
                    }

                    break;
                }
            }

            if ($fnsReceipt) {
                $check->fns_receipt_id = $fnsReceipt->id ?? 0;
                $check->products()->createMany($products);
                $check->save();
            }

            $bar->advance();
        }

        $bar->finish();
    }
}
