<?php

return [

    /*
     * Настройки писем
     */

    'mails_admins' => [
        'send' => true,
        'to' => [
            '',
        ],
        'subject' => 'Новое сообщение',
        'headers' => [],
    ],

    'queue' => [
        'enable' => false,
        'name' => 'feedback_notify',
    ],

];
