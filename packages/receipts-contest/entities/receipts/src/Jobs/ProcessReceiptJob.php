<?php

namespace Packages\ReceiptsContest\Receipts\Jobs;

use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use InetStudio\ReceiptsContest\Receipts\Contracts\Models\ReceiptModelContract;
use InetStudio\ReceiptsContest\Products\DTO\Back\Items\Attach\ItemData as ProductData;
use InetStudio\ReceiptsContest\Receipts\DTO\Back\Items\AddBarcodes\ItemData as AddBarcodesData;
use InetStudio\Fns\Receipts\Contracts\Services\ItemsServiceContract as FnsReceiptsServiceContract;
use InetStudio\ReceiptsContest\Receipts\DTO\Back\Items\AttachFnsReceipt\ItemData as AttachFnsReceiptData;
use InetStudio\ReceiptsContest\Receipts\Contracts\Services\Back\ItemsServiceContract as ReceiptsServiceContract;
use InetStudio\ReceiptsContest\Products\Contracts\Services\Back\ItemsServiceContract as ProductsServiceContract;

class ProcessReceiptJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected ReceiptsServiceContract $receiptsService;

    protected ProductsServiceContract $productsService;

    protected FnsReceiptsServiceContract $fnsReceiptsService;

    protected ReceiptModelContract $receipt;

    public function __construct(ReceiptModelContract $receipt)
    {
        $this->receipt = $receipt;

        $this->receiptsService = resolve('InetStudio\ReceiptsContest\Receipts\Contracts\Services\Back\ItemsServiceContract');
        $this->productsService = resolve('InetStudio\ReceiptsContest\Products\Contracts\Services\Back\ItemsServiceContract');
        $this->fnsReceiptsService = resolve('InetStudio\Fns\Receipts\Contracts\Services\ItemsServiceContract');
    }

    public function handle()
    {
        $receipt = $this->recognizeCodes($this->receipt);

        $receipt = $receipt->fresh();

        $this->attachFnsReceipt($receipt);
    }

    protected function recognizeCodes(ReceiptModelContract $receipt): ReceiptModelContract
    {
        if (! $receipt->hasJSONData('receipt_data', 'codes')) {
            $imagePath = $receipt->getFirstMediaPath('images');

            if (! $imagePath || ! file_exists($imagePath)) {
                return $receipt;
            }

            $client = new Client();

            $response = $client->request(
                'POST',
                config('services.recognize_barcodes_api.url'),
                [
                    'headers' => [
                        'Authorization' => 'Bearer '.config('services.recognize_barcodes_api.token'),
                        'Accept' => 'application/json',
                    ],
                    'multipart' => [
                        [
                            'name' => 'image',
                            'contents' => file_get_contents($imagePath),
                            'filename' => $imagePath
                        ],
                    ],
                ]
            );

            $codes = json_decode($response->getBody()->getContents(), true);

            $data = new AddBarcodesData(
                [
                    'id' => $receipt['id'],
                    'receipt_data' => [
                        'codes' => $codes,
                    ],
                ]
            );

            $this->receiptsService->addBarcodes($data);
        }

        return $receipt;
    }

    protected function attachFnsReceipt(ReceiptModelContract $receipt): ReceiptModelContract
    {
        $codes = $receipt->getJSONData('receipt_data', 'codes', []);

        $products = [];

        $fnsReceipt = null;
        if (empty($codes)) {
            return $receipt;
        }

        foreach ($codes as $code) {
            if (! (($code['type'] ?? '') === 'QR_CODE')) {
                continue;
            }

            if (! ($code['value'] ?? '')) {
                continue;
            }

            $fnsReceipt = null;

            $client = new Client();

            $response = $client->request(
                'POST',
                config('services.fns_api.url'),
                [
                    'headers' => [
                        'Authorization' => 'Bearer '.config('services.fns_api.token'),
                        'Accept' => 'application/json',
                    ],
                    'form_params' => [
                        'qr_code' => $code['value'],
                    ]
                ]
            );

            $fnsReceiptData = json_decode($response->getBody()->getContents(), true);

            if (isset($fnsReceiptData['receipt'])) {
                $data = resolve(
                    'InetStudio\Fns\Receipts\Contracts\DTO\ItemDataContract',
                    [
                        'args' => [
                            'qr_code' => $fnsReceiptData['receipt']['qr_code'],
                            'data' => $fnsReceiptData['receipt']['data'],
                        ]
                    ]
                );

                $fnsReceipt = $this->fnsReceiptsService->save($data);
            }

            if ($fnsReceipt) {
                $fnsReceiptData = $fnsReceipt->data['content'];

                foreach ($fnsReceiptData['items'] ?? [] as $item) {
                    $products[] = new ProductData(
                        [
                            'receipt_id' => $receipt->id,
                            'fns_receipt_id' => $fnsReceipt->id,
                            'name' => $item['name'],
                            'quantity' => (float) $item['quantity'],
                            'price' => (int) $item['price'],
                            'product_data' => $item,
                        ]
                    );
                }

                break;
            }
        }

        $data = new AttachFnsReceiptData(
            [
                'id' => $receipt['id'],
                'fns_receipt_id' => $fnsReceipt->id ?? null
            ]
        );

        $this->receiptsService->attachFnsReceipt($data);
        $this->productsService->attach($receipt, $products);

        return $receipt;
    }
}
