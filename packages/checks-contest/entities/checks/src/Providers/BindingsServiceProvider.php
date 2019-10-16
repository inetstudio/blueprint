<?php

namespace Packages\ChecksContest\Checks\Providers;

use Illuminate\Contracts\Foundation\Application;
use InetStudio\ChecksContest\Checks\Providers\BindingsServiceProvider as PackageBindingsServiceProvider;

/**
 * Class BindingsServiceProvider.
 */
final class BindingsServiceProvider extends PackageBindingsServiceProvider
{
    /**
     * BindingsServiceProvider constructor.
     *
     * @param  Application  $app
     */
    public function __construct(Application $app)
    {
        parent::__construct($app);

        $this->bindings['InetStudio\ChecksContest\Checks\Contracts\Console\Commands\AttachFnsReceiptsCommandContract'] = 'Packages\ChecksContest\Checks\Console\Commands\AttachFnsReceiptsCommand';
        $this->bindings['InetStudio\ChecksContest\Checks\Contracts\Console\Commands\RecognizeCodesCommandContract'] = 'Packages\ChecksContest\Checks\Console\Commands\RecognizeCodesCommand';
        $this->bindings['InetStudio\ChecksContest\Checks\Contracts\Console\Commands\SetWinnerCommandContract'] = 'Packages\ChecksContest\Checks\Console\Commands\SetWinnerCommand';
        $this->bindings['InetStudio\ChecksContest\Checks\Contracts\Exports\ItemsExportContract'] = 'Packages\ChecksContest\Checks\Exports\ItemsExport';
        $this->bindings['InetStudio\ChecksContest\Checks\Contracts\Exports\ItemsFullExportContract'] = 'Packages\ChecksContest\Checks\Exports\ItemsFullExport';
        $this->bindings['InetStudio\ChecksContest\Checks\Contracts\Http\Requests\Front\SaveItemRequestContract'] = 'Packages\ChecksContest\Checks\Http\Requests\Front\SaveItemRequest';
        $this->bindings['InetStudio\ChecksContest\Checks\Contracts\Services\Front\ItemsServiceContract'] = 'Packages\ChecksContest\Checks\Services\Front\ItemsService';
    }
}
