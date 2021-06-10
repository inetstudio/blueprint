<?php

namespace App\Events;

use InetStudio\ACL\Users\Contracts\Models\UserModelContract;

class AuditEvent
{
    public string $event;

    public ?UserModelContract $user;

    public function __construct(string $event, ?UserModelContract $user)
    {
        $this->event = $event;
        $this->user = $user;
    }
}
