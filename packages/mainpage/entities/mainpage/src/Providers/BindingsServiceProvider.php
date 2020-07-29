<?php

namespace Packages\MainPagePackage\MainPage\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Support\DeferrableProvider;

final class BindingsServiceProvider extends ServiceProvider implements DeferrableProvider
{
    public array $bindings = [
        'MainPageService' => 'Packages\MainPagePackage\MainPage\Services\Front\MainPageService',
    ];

    public function provides()
    {
        return array_keys($this->bindings);
    }
}
