<?php

namespace Packages\ChecksContest\Checks\Exports;

use Illuminate\Support\Carbon;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use InetStudio\ChecksContest\Checks\Exports\ItemsExport as PackageItemsExport;

/**
 * Class ItemsExport.
 */
final class ItemsExport extends PackageItemsExport
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

        $checkData = $check->additional_info;

        return [
            $check->id,
            $status,
            ($check->getJSONData('receipt_data', 'duplicate', '') == 'true') ? 'Дубликат' : $check->getJSONData('receipt_data', 'denyReason', ''),
            $prizes,
            trim($prizesDates, ', '),
            trim($confirmed, ', '),
            $checkData['name'] ?? '',
            $checkData['phone'] ?? '',
            $check->getJSONData('receipt_data', 'cityName', ''),
            Date::dateTimeToExcel($check->created_at),
            url($fileUrl),
        ];
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID',
            'Статус',
            'Причина отмены',
            'Призы',
            'Дата приза',
            'Победитель подтвержден',
            'Имя',
            'Телефон',
            'Город',
            'Дата регистрации',
            'Ссылка на чек',
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
        ];
    }
}
