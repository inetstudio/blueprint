<?php

namespace Packages\PagesPackage\Pages\Providers;

use Illuminate\Contracts\Foundation\Application;
use InetStudio\PagesPackage\Pages\Providers\BindingsServiceProvider as PackageBindingsServiceProvider;

final class BindingsServiceProvider extends PackageBindingsServiceProvider
{
    public function __construct(Application $app)
    {
        parent::__construct($app);

        $this->bindings['InetStudio\PagesPackage\Pages\Contracts\Services\Front\ItemsServiceContract'] = 'Packages\PagesPackage\Pages\Services\Front\ItemsService';
    }
}
