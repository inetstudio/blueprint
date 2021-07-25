<?php

namespace Packages\ReceiptsContest\Receipts\Exports;

use App\Events\AuditEvent;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use InetStudio\ReceiptsContest\Receipts\Exports\ItemsExport as PackageItemsExport;

class ItemsExport extends PackageItemsExport
{
    public function query()
    {
        event(new AuditEvent('receipts_download', Auth::user()));

        return $this->itemsService->getModel()->query()->with(['media', 'status', 'fnsReceipt']);
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
            $confirmed .= ', '.(($prize->pivot['confirmed'] === 1) ? 'Да' : 'Нет');
        }

        $address = ($fnsReceipt) ? $fnsReceipt->getJSONData('data', 'content.retailPlaceAddress', '') : '';
        $address = (! $address && $fnsReceipt) ? $fnsReceipt->getJSONData('data', 'address', '') : $address;
        $address = (! $address) ? $item->getJSONData('additional_info', 'retailPlaceAddress', '') : $address;

        return [
            $item->id,
            $status,
            $item->getJSONData('receipt_data', 'statusReason', ''),
            $prizes,
            trim($prizesDates, ', '),
            trim($confirmed, ', '),
            $address,
            $item->getJSONData('additional_info', 'name', ''),
            $item->getJSONData('additional_info', 'phone', ''),
            $item->getJSONData('additional_info', 'email', ''),
            Date::dateTimeToExcel($item->created_at),
            ($fileUrl) ? url($fileUrl) : '',
        ];
    }

    public function headings(): array
    {
        return [
            'ID',
            'Статус',
            'Причина отмены',
            'Призы',
            'Дата приза',
            'Победитель подтвержден',
            'Место покупки',
            'Имя',
            'Телефон',
            'E-mail',
            'Дата регистрации',
            'Ссылка на чек',
        ];
    }

    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_NUMBER,
            'L' => NumberFormat::FORMAT_DATE_DATETIME,
        ];
    }
}
