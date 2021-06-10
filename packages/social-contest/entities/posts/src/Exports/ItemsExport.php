<?php

namespace Packages\SocialContest\Posts\Exports;

use App\Events\AuditEvent;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use InetStudio\SocialContest\Posts\Exports\ItemsExport as PackageItemsExport;

class ItemsExport extends PackageItemsExport
{
    public function query()
    {
        event(new AuditEvent('social_posts_download', Auth::user()));

        return $this->itemsService->getModel()->query()
            ->with(['social', 'status'])
            ->where('search_data', 'like', '%артбуздомашка%')
            ->where('search_data', 'like', '%"media_type": 2%');
    }

    public function map($item): array
    {
        $fileUrl = ($item['social']->hasMedia('media')) ? url($item['social']->getFirstMediaUrl('media')) : '';

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

        return [
            $item['id'],
            $item['status']['name'],
            $item->getJSONData('additional_info', 'statusReason', ''),
            $prizes,
            trim($prizesDates, ', '),
            trim($confirmed, ', '),
            $item['social']['social_name'],
            $item['social']['user']['nickname'],
            $item['social']['user']['url'],
            $item['social']['url'],
            $item['social']['caption'],
            Date::dateTimeToExcel(\Carbon\Carbon::createFromTimestamp($item['social']['additional_info']['taken_at'])),
            Date::dateTimeToExcel($item['created_at']),
            Date::dateTimeToExcel($item['updated_at']),
            ($fileUrl) ? url($fileUrl) : '',
        ];
    }

    public function headings(): array
    {
        return [
            'ID',
            'Статус',
            'Причина перевода на статус',
            'Призы',
            'Дата приза',
            'Победитель подтвержден',
            'Социальная сеть',
            'Пользователь',
            'Ссылка на пользователя',
            'Ссылка на пост',
            'Содержимое',
            'Дата загрузки в соц.сеть',
            'Дата регистрации в системе',
            'Дата обновления',
            'Ссылка на медиа',
        ];
    }

    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_NUMBER,
            'L' => NumberFormat::FORMAT_DATE_DATETIME,
            'M' => NumberFormat::FORMAT_DATE_DATETIME,
            'N' => NumberFormat::FORMAT_DATE_DATETIME,
        ];
    }
}
