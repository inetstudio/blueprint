<?php

namespace Packages\SocialContest\Posts\Providers;

use Illuminate\Contracts\Foundation\Application;
use InetStudio\SocialContest\Posts\Providers\BindingsServiceProvider as PackageBindingsServiceProvider;

final class BindingsServiceProvider extends PackageBindingsServiceProvider
{
    public function __construct(Application $app)
    {
        parent::__construct($app);

        $this->bindings['InetStudio\SocialContest\Posts\Contracts\Exports\ItemsExportContract'] = 'Packages\SocialContest\Posts\Exports\ItemsExport';
        $this->bindings['InetStudio\SocialContest\Posts\Contracts\Http\Resources\Back\Resource\Index\ItemResourceContract'] = 'Packages\SocialContest\Posts\Http\Resources\Back\Resource\Index\ItemResource';
        $this->bindings['InetStudio\SocialContest\Posts\Contracts\Services\Back\DataTables\IndexServiceContract'] = 'Packages\SocialContest\Posts\Services\Back\DataTables\IndexService';
    }
}
