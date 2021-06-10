<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\AuditEvent' => [
            'App\Listeners\LogActionListener',
        ],
        'InetStudio\PagesPackage\Pages\Contracts\Events\Back\ModifyItemEventContract' => [
            'Packages\PagesPackage\Pages\Listeners\Front\ClearItemCache',
        ],
        'InetStudio\FeedbackPackage\Feedback\Contracts\Events\Front\SendItemEventContract' => [
            'InetStudio\FeedbackPackage\Feedback\Contracts\Listeners\SendEmailToAdminListenerContract',
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
