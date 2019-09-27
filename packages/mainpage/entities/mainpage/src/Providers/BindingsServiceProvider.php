<?php

namespace Packages\MainPagePackage\MainPage\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Support\DeferrableProvider;

/**
 * Class BindingsServiceProvider.
 */
final class BindingsServiceProvider extends ServiceProvider implements DeferrableProvider
{
    public $bindings = [
        'MainPageService' => 'Packages\MainPagePackage\MainPage\Services\Front\MainPageService',
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
