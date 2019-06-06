<?php

namespace App\Providers\Bindings\PagesPackage\Pages;

use Illuminate\Contracts\Foundation\Application;
use InetStudio\PagesPackage\Pages\Providers\BindingsServiceProvider as PackageBindingsServiceProvider;

/**
 * Class BindingsServiceProvider.
 */
class BindingsServiceProvider extends PackageBindingsServiceProvider
{
    /**
     * BindingsServiceProvider constructor.
     *
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        parent::__construct($app);

        $this->bindings['InetStudio\PagesPackage\Pages\Contracts\Services\Front\ItemsServiceContract'] = 'App\Services\Front\PagesPackage\Pages\ItemsService';
    }
}
