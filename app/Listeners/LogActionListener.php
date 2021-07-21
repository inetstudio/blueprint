<?php

namespace App\Listeners;

use Carbon\Carbon;
use OwenIt\Auditing\Models\Audit;

class LogActionListener
{
    public function handle($event): void
    {
        $action = $event->event;
        $user = $event->user;

        $data = [
            'user_type' => ($user) ? get_class($user) : '',
            'user_id' => ($user) ? $user->id : 0,
            'event' => $action,
            'auditable_type' => 'manual',
            'auditable_id' => 0,
            'old_values' => [],
            'new_values' => [],
            'url' => request()->fullUrl(),
            'ip_address' => request()->getClientIp(),
            'user_agent' => request()->userAgent(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];

        Audit::create($data);
    }
}
