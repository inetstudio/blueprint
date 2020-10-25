<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        'InetStudio\FeedbackPackage\Feedback\Contracts\Events\Front\SendItemEventContract' => [
            'InetStudio\FeedbackPackage\Feedback\Contracts\Listeners\SendEmailToAdminListenerContract',
        ],
        'InetStudio\PagesPackage\Pages\Contracts\Events\Back\ModifyItemEventContract' => [
            'Packages\PagesPackage\Pages\Listeners\Front\ClearItemCache',
        ],
        'InetStudio\ReceiptsContest\Receipts\Contracts\Events\Back\ModerateItemEventContract' => [
            'InetStudio\ReceiptsContest\Receipts\Contracts\Listeners\ItemStatusChangeListenerContract'
        ],
        'InetStudio\ReceiptsContest\Receipts\Contracts\Events\Front\SendItemEventContract' => [
            'InetStudio\ReceiptsContest\Receipts\Contracts\Listeners\ItemStatusChangeListenerContract'
        ],
        'InetStudio\ReceiptsContest\Receipts\Contracts\Events\Back\SetWinnerEventContract' => [
            'InetStudio\ReceiptsContest\Receipts\Contracts\Listeners\Back\SetWinnerListenerContract'
        ],
    ];

    public function boot()
    {
    }
}
