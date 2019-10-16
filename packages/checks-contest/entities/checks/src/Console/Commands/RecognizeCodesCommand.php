<?php

namespace Packages\ChecksContest\Checks\Console\Commands;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Contracts\Container\BindingResolutionException;
use InetStudio\ChecksContest\Checks\Console\Commands\RecognizeCodesCommand as PackageRecognizeCodesCommand;

/**
 * Class RecognizeCodesCommand.
 */
class RecognizeCodesCommand extends PackageRecognizeCodesCommand
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
        $checksService = app()->make('InetStudio\ChecksContest\Checks\Contracts\Services\Back\ItemsServiceContract');
        $statusesService = app()->make('InetStudio\ChecksContest\Statuses\Contracts\Services\Back\ItemsServiceContract');

        $status = $statusesService->getDefaultStatus();

        $checks = $checksService->getModel()->where([
            ['status_id', '=', $status->id],
        ])->get();

        $client = new Client();

        $bar = $this->output->createProgressBar(count($checks));

        foreach ($checks as $check) {
            if (! $check->hasJSONData('receipt_data', 'codes')) {
                $imagePath = $check->getFirstMediaPath('images');

                $response = $client->request(
                    'POST',
                    config('checks_contest_checks.recognize_barcode_service'),
                    [
                        'headers' => [
                            'Authorization' => 'Bearer '.config('checks_contest_checks.services_token'),
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

                $check->setJSONData('receipt_data', 'codes', $codes);
                $check->save();
            }

            $bar->advance();
        }

        $bar->finish();
    }
}
