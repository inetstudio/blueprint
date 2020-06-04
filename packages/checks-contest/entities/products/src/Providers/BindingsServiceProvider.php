<?php

namespace Packages\ReceiptsContest\Products\Providers;

use Illuminate\Contracts\Foundation\Application;
use InetStudio\ReceiptsContest\Products\Providers\BindingsServiceProvider as PackageBindingsServiceProvider;

final class BindingsServiceProvider extends PackageBindingsServiceProvider
{
    public function __construct(Application $app)
    {
        parent::__construct($app);

        //$this->bindings['InetStudio\ReceiptsContest\Products\Contracts\Transformers\Back\Resource\ShowTransformerContract'] = 'Packages\ReceiptsContest\Products\Transformers\Back\Resource\ShowTransformer';
    }
}
