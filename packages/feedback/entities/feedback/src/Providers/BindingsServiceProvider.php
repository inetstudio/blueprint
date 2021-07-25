<?php

namespace Packages\FeedbackPackage\Feedback\Providers;

use Illuminate\Contracts\Foundation\Application;
use InetStudio\FeedbackPackage\Feedback\Providers\BindingsServiceProvider as PackageBindingsServiceProvider;

final class BindingsServiceProvider extends PackageBindingsServiceProvider
{
    public function __construct(Application $app)
    {
        parent::__construct($app);

        $this->bindings['InetStudio\FeedbackPackage\Feedback\Contracts\Http\Requests\Front\SendItemRequestContract'] = 'Packages\FeedbackPackage\Feedback\Http\Requests\Front\SendItemRequest';
    }
}
