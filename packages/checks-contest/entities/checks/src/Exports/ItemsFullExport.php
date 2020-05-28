<?php

namespace Packages\ChecksContest\Checks\Exports;

use Illuminate\Support\Carbon;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use InetStudio\ChecksContest\Checks\Exports\ItemsExport as PackageItemsExport;

/**
 * Class ItemsFullExport.
 */
final class ItemsFullExport extends PackageItemsExport
{
    /**
     * @param $check
     *
     * @return array
     */
    public function map($check): array
    {
        $fileUrl = $check->getFirstMediaUrl('images');

        $status = $check->status->name;
        $prizes = ($check->prizes->count() > 0) ? implode(', ', $check->prizes->pluck('name')->toArray()) : '';

        $prizesDates = '';

        foreach ($check->prizes as $prize) {
            $date = '';
            $date .= ($prize->pivot['date_start']) ? Carbon::createFromFormat('Y-m-d H:i:s', $prize->pivot['date_start'])->format('d.m.Y') : '';
            $date .= ($prize->pivot['date_end']) ? ' - '.Carbon::createFromFormat('Y-m-d H:i:s', $prize->pivot['date_end'])->format('d.m.Y') : '';

            $prizesDates .= ', '.$date;
        }

        $confirmed = '';
        foreach ($check->prizes as $prize) {
            $confirmed .= ', '.(($prize->pivot['confirmed'] == 1) ? 'Да' : 'Нет');
        }

        $products = $check->products;

        $data = [];

        if (count($products) > 0) {
            foreach ($products ?? [] as $index => $product) {
                $rowData = array_fill(0, 18, '');
                if ($index == 0) {
                    $discount = $check->getJSONData('receipt_data', 'discountSum', 0);
                    $discount = str_replace(',', '.', $discount);
                    $discount = (! is_numeric($discount)) ? 0 : $discount;
                    $discount = (float) number_format($discount, 2, '.', '');

                    $rowData[0] = $check->id;
                    $rowData[1] = $status;
                    $rowData[2] = ($check->getJSONData('receipt_data', 'duplicate', '') == 'true') ? 'Дубликат' : $check->getJSONData('receipt_data', 'denyReason', '');
                    $rowData[3] = $prizes;
                    $rowData[4] = trim($prizesDates, ', ');
                    $rowData[5] = trim($confirmed, ', ');
                    $rowData[6] = $check->getJSONData('additional_info', 'name', '');
                    $rowData[7] = $check->getJSONData('additional_info', 'phone', '');
                    $rowData[8] = $check->getJSONData('receipt_data', 'cityName', '');
                    $rowData[9] = Date::dateTimeToExcel($check['created_at']);
                    $rowData[10] = url($fileUrl);
                    $rowData[11] = $check->getJSONData('receipt_data', 'retailName', '');
                    $rowData[16] = $discount;
                    $rowData[17] = $products->sum('sum') - $discount;
                }

                $rowData[12] = $product->getJSONData('product_data', 'category', '');
                $rowData[13] = $product['name'];
                $rowData[14] = $product['quantity'];
                $rowData[15] = $product['price_formatted'];

                $data[] = $rowData;
            }
        } else {
            $rowData = array_fill(0, 18, '');
            $rowData[0] = $check->id;
            $rowData[1] = $status;
            $rowData[2] = ($check->getJSONData('receipt_data', 'duplicate', '') == 'true') ? 'Дубликат' : $check->getJSONData('receipt_data', 'denyReason', '');
            $rowData[3] = $prizes;
            $rowData[4] = trim($prizesDates, ', ');
            $rowData[5] = trim($confirmed, ', ');
            $rowData[6] = $check->getJSONData('additional_info', 'name', '');
            $rowData[7] = $check->getJSONData('additional_info', 'phone', '');
            $rowData[8] = $check->getJSONData('receipt_data', 'cityName', '');
            $rowData[9] = Date::dateTimeToExcel($check['created_at']);
            $rowData[10] = url($fileUrl);
            $rowData[11] = $check->getJSONData('receipt_data', 'retailName', '');
            $rowData[16] = $check->getJSONData('receipt_data', 'discountSum', 0);
            $rowData[17] = $products->sum('sum') - $check->getJSONData('receipt_data', 'discountSum', 0);

            $data[] = $rowData;
        }

        return $data;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID',
            'Статус чека',
            'Причина отмены',
            'Призы',
            'Дата приза',
            'Победитель подтвержден',
            'Имя',
            'Телефон',
            'Город',
            'Дата регистрации',
            'Ссылка на чек',
            'Сеть',
            'Категория товара',
            'Название продукта',
            'Количество продуктов',
            'Цена продукта (за единицу товара), руб.',
            'Скидка, руб.',
            'Общая сумма чека, руб.',
        ];
    }

    /**
     * @return array
     */
    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_NUMBER,
            'J' => NumberFormat::FORMAT_DATE_DATETIME,
            'P' => NumberFormat::FORMAT_NUMBER_00,
            'Q' => NumberFormat::FORMAT_NUMBER_00,
            'R' => NumberFormat::FORMAT_NUMBER_00,
        ];
    }
}
