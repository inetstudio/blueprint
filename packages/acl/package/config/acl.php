<?php

return [

    'user_models' => [
        'users' => 'InetStudio\ACL\Users\Models\UserModel',
    ],

    'models' => [
        'role' => 'InetStudio\ACL\Roles\Models\RoleModel',
        'permission' => 'InetStudio\ACL\Permissions\Models\PermissionModel',
    ],

    'activations' => [
        'enabled' => true,
        'login_after_activate' => false,
        'mails' => [
            'subject' => 'Активация аккаунта',
        ],
    ],

    'passwords' => [
        'mails' => [
            'subject' => 'Сброс пароля',
        ],
    ],

];
