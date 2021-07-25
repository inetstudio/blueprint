<?php

namespace Packages\ReceiptsContest\Receipts\Exports;

use App\Events\AuditEvent;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use InetStudio\ReceiptsContest\Receipts\Exports\ItemsFullExport as PackageItemsFullExport;

class ItemsFullExport extends PackageItemsFullExport
{
    public function query()
    {
        event(new AuditEvent('receipts_full_download', Auth::user()));

        return $this->itemsService->getModel()->query()->with(['media', 'status', 'prizes', 'products', 'fnsReceipt']);
    }

    public function map($item): array
    {
        $fileUrl = $item->getFirstMediaUrl('images');

        $status = $item->status->name;
        $fnsReceipt = $item->fnsReceipt;
        $prizes = ($item->prizes->count() > 0) ? implode(', ', $item->prizes->pluck('name')->toArray()) : '';

        $prizesDates = '';

        foreach ($item->prizes as $prize) {
            $date = '';
            $date .= ($prize->pivot['date_start']) ? Carbon::createFromFormat('Y-m-d H:i:s', $prize->pivot['date_start'])->format('d.m.Y') : '';
            $date .= ($prize->pivot['date_end']) ? ' - '.Carbon::createFromFormat('Y-m-d H:i:s', $prize->pivot['date_end'])->format('d.m.Y') : '';

            $prizesDates .= ', '.$date;
        }

        $confirmed = '';
        foreach ($item->prizes as $prize) {
            $confirmed .= ', '.(($prize->pivot['confirmed'] == 1) ? 'Да' : 'Нет');
        }

        $products = $item->products;

        $data = [];

        $address = ($fnsReceipt) ? $fnsReceipt->getJSONData('data', 'content.retailPlaceAddress', '') : '';
        $address = (! $address && $fnsReceipt) ? $fnsReceipt->getJSONData('data', 'address', '') : $address;
        $address = (! $address) ? $item->getJSONData('additional_info', 'retailPlaceAddress', '') : $address;

        if (count($products) > 0) {
            foreach ($products ?? [] as $index => $product) {
                $rowData = array_fill(0, 18, '');
                if ($index == 0) {
                    $rowData[0] = $item->id;
                    $rowData[1] = $status;
                    $rowData[2] = $item->getJSONData('receipt_data', 'statusReason', '');
                    $rowData[3] = $prizes;
                    $rowData[4] = trim($prizesDates, ', ');
                    $rowData[5] = trim($confirmed, ', ');
                    $rowData[6] = $address;
                    $rowData[7] = $item->getJSONData('additional_info', 'name', '');
                    $rowData[8] = $item->getJSONData('additional_info', 'phone', '');
                    $rowData[9] = $item->getJSONData('additional_info', 'email', '');
                    $rowData[10] = Date::dateTimeToExcel($item['created_at']);
                    $rowData[11] = url($fileUrl);
                    $rowData[16] = $products->sum('sum');
                }

                $rowData[12] = $product->getJSONData('product_data', 'category', '');
                $rowData[13] = $product['name'];
                $rowData[14] = $product['quantity'];
                $rowData[15] = (float) number_format(($product['price'] / 100), 2, '.', '');

                $data[] = $rowData;
            }
        } else {
            $rowData = array_fill(0, 18, '');
            $rowData[0] = $item->id;
            $rowData[1] = $status;
            $rowData[2] = $item->getJSONData('receipt_data', 'statusReason', '');
            $rowData[3] = $prizes;
            $rowData[4] = trim($prizesDates, ', ');
            $rowData[5] = trim($confirmed, ', ');
            $rowData[6] = $address;
            $rowData[7] = $item->getJSONData('additional_info', 'name', '');
            $rowData[8] = $item->getJSONData('additional_info', 'phone', '');
            $rowData[9] = $item->getJSONData('additional_info', 'email', '');
            $rowData[10] = Date::dateTimeToExcel($item['created_at']);
            $rowData[11] = url($fileUrl);
            $rowData[16] = $products->sum('sum');

            $data[] = $rowData;
        }

        return $data;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Статус чека',
            'Причина отмены',
            'Призы',
            'Дата приза',
            'Победитель подтвержден',
            'Место покупки',
            'Имя',
            'Телефон',
            'E-mail',
            'Номер карты',
            'Дата регистрации',
            'Ссылка на чек',
            'Категория товара',
            'Название продукта',
            'Количество продуктов',
            'Цена продукта (за единицу товара), руб.',
            'Общая сумма чека, руб.',
        ];
    }

    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_NUMBER,
            'L' => NumberFormat::FORMAT_DATE_DATETIME,
            'Q' => NumberFormat::FORMAT_NUMBER_00,
            'R' => NumberFormat::FORMAT_NUMBER_00,
        ];
    }
}
