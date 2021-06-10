<?php

declare(strict_types=1);

namespace Packages\SocialContest\Posts\Services\Back\DataTables;

use InetStudio\SocialContest\Posts\Services\Back\DataTables\IndexService as PackageIndexService;

class IndexService extends PackageIndexService
{
    protected function getColumns(): array
    {
        return [
            ['data' => 'search_data', 'name' => 'search_data', 'title' => 'Search', 'orderable' => false, 'visible' => false, 'className' => 'post-search_data'],
            ['data' => 'uuid', 'name' => 'uuid', 'title' => 'uuid', 'visible' => false, 'className' => 'post-uuid'],
            ['data' => 'id', 'name' => 'id', 'title' => 'ID', 'className' => 'post-id'],
            ['data' => 'status', 'name' => 'status.name', 'title' => 'Статус', 'orderable' => false, 'className' => 'post-status'],
            ['data' => 'moderation', 'name' => 'moderation', 'title' => 'Модерация', 'orderable' => false, 'searchable' => false, 'className' => 'post-moderation'],
            ['data' => 'prizes', 'name' => 'prizes.name', 'title' => 'Призы', 'orderable' => false, 'className' => 'post-prizes'],
            ['data' => 'media', 'name' => 'media', 'title' => 'Медиа', 'orderable' => false, 'searchable' => false, 'className' => 'post-media'],
            ['data' => 'info', 'name' => 'info', 'title' => 'Инфо', 'orderable' => false, 'searchable' => false, 'className' => 'post-info'],
            ['data' => 'taken_at', 'name' => 'taken_at', 'title' => 'Дата загрузки', 'className' => 'post-taken_at', 'orderable' => false, 'searchable' => false,],
            ['data' => 'created_at', 'name' => 'created_at', 'title' => 'Дата создания', 'className' => 'post-created_at'],
            ['data' => 'updated_at', 'name' => 'updated_at', 'title' => 'Дата обновления', 'className' => 'post-updated_at'],
            ['data' => 'actions', 'name' => 'actions', 'title' => 'Действия', 'orderable' => false, 'searchable' => false, 'className' => 'post-actions'],
        ];
    }

    protected function getAjaxOptions(): array
    {
        return [
            'url' => route('back.social-contest.posts.data.index'),
            'type' => 'POST',
        ];
    }

    protected function getParameters(): array
    {
        $translation = trans('admin::datatables');

        return [
            'order' => [9, 'desc'],
            'paging' => true,
            'pagingType' => 'full_numbers',
            'searching' => true,
            'info' => false,
            'searchDelay' => 350,
            'language' => $translation,
        ];
    }
}
