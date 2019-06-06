<?php

namespace App\Providers\Bindings;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Support\DeferrableProvider;

/**
 * Class AppBindingsServiceProvider.
 */
class AppBindingsServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * @var array
     */
    public $bindings = [
        'CacheService' => 'App\Services\Front\Cache\CacheService',
    ];

    /**
     * Получить сервисы от провайдера.
     *
     * @return array
     */
    public function provides()
    {
        return array_keys($this->bindings);
    }
}
