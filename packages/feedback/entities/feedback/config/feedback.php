<?php

return [

    /*
     * Настройки писем
     */

    'mails' => [
        'to' => '',
        'subject' => 'Сообщение с формы обратной связи',
        'headers' => [],
    ],

    'mails_users' => [
        'send' => true,
        'subject' => 'Ответ на сообщение',
        'headers' => [],
    ],

    'queue' => [
        'enable' => false,
        'name' => 'feedback_notify',
    ],

];
