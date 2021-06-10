<?php

namespace Packages\SocialContest\Posts\Http\Resources\Back\Resource\Index;

use Carbon\Carbon;
use InetStudio\SocialContest\Posts\Http\Resources\Back\Resource\Index\ItemResource as PackageItemResource;

class ItemResource extends PackageItemResource
{
    public function toArray($request)
    {
        return [
            'DT_RowId' => 'post_row_'.$this['id'],
            'search_data' => $this['search_data'],
            'uuid' => $this['uuid'],
            'id' => $this['id'],
            'status' => view(
                'admin.module.social-contest.posts::back.partials.datatables.status',
                [
                    'item' => $this,
                ]
            )->render(),
            'moderation' => view(
                'admin.module.social-contest.posts::back.partials.datatables.moderation',
                [
                    'item' => $this,
                ]
            )->render(),
            'prizes' => view(
                'admin.module.social-contest.posts::back.partials.datatables.prizes',
                [
                    'item' => $this
                ]
            )->render(),
            'media' => view(
                'admin.module.social-contest.posts::back.partials.datatables.media',
                [
                    'item' => $this,
                ]
            )->render(),
            'info' => view(
                'admin.module.social-contest.posts::back.partials.datatables.info',
                [
                    'item' => $this,
                ]
            )->render(),
            'taken_at' => (string) Carbon::createFromTimestamp($this['social']['additional_info']['taken_at']),
            'created_at' => (string) $this['created_at'],
            'updated_at' => (string) $this['updated_at'],
            'actions' => view(
                'admin.module.social-contest.posts::back.partials.datatables.actions',
                [
                    'item' => $this,
                ]
            )->render(),
        ];
    }
}
