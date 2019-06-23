<?php

namespace Packages\CachePackage\Cache\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Support\DeferrableProvider;

/**
 * Class BindingsServiceProvider.
 */
class BindingsServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * @var array
     */
    public $bindings = [
        'CacheService' => 'Packages\CachePackage\Cache\Services\Front\Cache\CacheService',
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
