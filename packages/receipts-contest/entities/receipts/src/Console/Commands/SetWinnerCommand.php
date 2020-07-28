<?php

namespace Packages\ReceiptsContest\Receipts\Console\Commands;

use SimpleXMLElement;
use GuzzleHttp\Client;
use Illuminate\Support\Collection;
use InetStudio\ReceiptsContest\Receipts\Console\Commands\SetWinnerCommand as PackageSetWinnerCommand;

class SetWinnerCommand extends PackageSetWinnerCommand
{
    protected function initOptions(): void
    {
        $this->drawOptions['euroValue'] = $this->getEuroValue();
    }

    protected function getEuroValue(): float
    {
        $client = new Client();

        $cbrResponse = $client->get('http://www.cbr.ru/scripts/XML_daily.asp', [
            'query' => [
                'date_req' => date('d/m/Y'),
            ],
        ]);

        $currencies = new SimpleXMLElement($cbrResponse->getBody()->getContents());
        $eur = $currencies->xpath('Valute[@ID="R01239"]/Value')[0];

        return (float) str_replace(',', '.', $eur);
    }

    protected function getWinnersReceipts(Collection $receipts, array $prizeData): Collection
    {
        $indexes = [];

        if ($receipts->count() <= $prizeData['count']) {
            $indexes = range(0, $receipts->count() - 1);
        } else {
            if ($prizeData['prize'] == 'spa') {
                for ($i = 1; $i <= $prizeData['count']; $i++) {
                    $indexes[] = (int) ($i * floor($receipts->count() / $prizeData['count'])) - 1;
                }
            } else {
                $index = (int) floor($receipts->count() * ($this->drawOptions['euroValue'] - floor($this->drawOptions['euroValue']))) - 1;

                $indexes[] = ($index < 0 ) ? 0 : $index;
            }
        }

        $winnersPhones = [];
        $winnersEmails = [];
        $winnersReceipts = collect();

        foreach ($receipts as $index => $receipt) {
            if (in_array($index, $indexes)) {
                $data = $receipt->additional_info;

                if (! (in_array($data['phone'], $winnersPhones)) && ! (in_array($data['email'], $winnersEmails))) {
                    $winnersPhones[] = $data['phone'];
                    $winnersEmails[] = $data['email'];

                    $winnersReceipts->push($receipt);
                } elseif ($index == ($receipts->count() - 1)) {
                    $previousIndex = $this->getPreviousIndex($index, $indexes);

                    if (isset($receipts[$previousIndex])) {
                        $winnersReceipts->push($receipts[$previousIndex]);
                    }
                } else {
                    $indexes[] = $this->getNextIndex($index, $indexes);
                }
            }
        }

        return $winnersReceipts;
    }
}
