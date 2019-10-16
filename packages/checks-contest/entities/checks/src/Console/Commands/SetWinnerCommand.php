<?php

namespace Packages\ChecksContest\Checks\Console\Commands;

use Carbon\Carbon;
use SimpleXMLElement;
use GuzzleHttp\Client;
use Illuminate\Support\Collection;
use Illuminate\Contracts\Container\BindingResolutionException;
use InetStudio\ChecksContest\Checks\Console\Commands\SetWinnerCommand as PackageSetWinnerCommand;

/**
 * Class SetWinnerCommand.
 */
class SetWinnerCommand extends PackageSetWinnerCommand
{
    /**
     * Инициализируем необходимые опции.
     */
    protected function initOptions(): void
    {
        $this->drawOptions['euroValue'] = $this->getEuroValue();
    }

    /**
     * Возвращаем курс EUR к рублю.
     *
     * @return float
     */
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

    /**
     * Получаем чеки победителей.
     *
     * @param  Collection  $checks
     * @param  array  $prizeData
     *
     * @return Collection
     */
    protected function getWinnersChecks(Collection $checks, array $prizeData): Collection
    {
        $indexes = [];

        if ($checks->count() <= $prizeData['count']) {
            $indexes = range(0, $checks->count() - 1);
        } else {
            if ($prizeData['prize'] == 'main') {
                $indexes[] = (int) floor($checks->count() * ($this->drawOptions['euroValue'] - floor($this->drawOptions['euroValue']))) - 1;
            } else {
                for ($i = 1; $i <= $prizeData['count']; $i++) {
                    $indexes[] = (int) ($i * floor($checks->count() / $prizeData['count'])) - 1;
                }
            }
        }

        $winnersPhones = [];
        $winnersChecks = collect();

        foreach ($checks as $index => $check) {
            if (in_array($index, $indexes)) {
                $data = $check->additional_info;

                if (! (in_array($data['phone'], $winnersPhones))) {
                    $winnersPhones[] = $data['phone'];

                    $winnersChecks->push($check);
                } elseif ($index == ($checks->count() - 1)) {
                    $previousIndex = $this->getPreviousIndex($index, $indexes);

                    if (isset($checks[$previousIndex])) {
                        $winnersChecks->push($checks[$previousIndex]);
                    }
                } else {
                    $indexes[] = $this->getNextIndex($index, $indexes);
                }
            }
        }

        return $winnersChecks;
    }


    /**
     * Получаем индекс следующего чека.
     *
     * @param  int  $index
     * @param  array  $indexes
     *
     * @return int
     */
    protected function getNextIndex(int $index, array $indexes): int
    {
        while (in_array($index, $indexes)) {
            $index++;
        }

        return $index;
    }

    /**
     * Получаем индекс предыдущего чека.
     *
     * @param  int  $index
     * @param  array  $indexes
     *
     * @return int
     */
    protected function getPreviousIndex(int $index, array $indexes): int
    {
        while (in_array($index, $indexes)) {
            $index--;
        }

        return $index;
    }
}
