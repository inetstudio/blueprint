<?php

namespace Packages\ChecksContest\Products\Providers;

use Illuminate\Contracts\Foundation\Application;
use InetStudio\ChecksContest\Products\Providers\BindingsServiceProvider as PackageBindingsServiceProvider;

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

        $this->bindings['InetStudio\ChecksContest\Products\Contracts\Transformers\Back\Resource\ShowTransformerContract'] = 'Packages\ChecksContest\Products\Transformers\Back\Resource\ShowTransformer';
    }
}
