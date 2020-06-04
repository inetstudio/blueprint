<?php

namespace Packages\ReceiptsContest\Receipts\Listeners;

use Illuminate\Support\Facades\Mail;
use GuzzleHttp\Exception\BadResponseException;
use InetStudio\ReceiptsContest\Receipts\Contracts\Listeners\ItemStatusChangeListenerContract;

/**
 * Class ItemStatusChangeListener.
 */
class ItemStatusChangeListener implements ItemStatusChangeListenerContract
{
    /**
     * Заголовки писем.
     *
     * @var array
     */
    protected $subjects = [
        'moderation' => 'Конкурс L\'Oréal Paris: Ваш чек на модерации',
        'approved' => 'Конкурс L\'Oréal Paris: Ваш чек одобрен',
        'rejected' => 'Конкурс L\'Oréal Paris: Ваш чек отклонен',
    ];

    /**
     * Handle the event.
     *
     * @param  object  $event
     */
    public function handle($event): void
    {
        $item = $event->item;
        $statusAlias = $item->status->alias;

        $email = $item->additional_info['email'];
        $name = $item->additional_info['name'];

        $subject = $this->subjects[$statusAlias] ?? '';

        if (! $subject) {
            return;
        }

        try {
            Mail::send(
                'admin.module.checks-contest.checks::mails.'.$statusAlias, compact('name'),
                function ($m) use ($email, $name, $subject) {
                    $m->from(config('mail.from.address'), config('mail.from.name'));
                    $m->to($email, $name)->subject($subject);
                }
            );
        } catch (BadResponseException $e) {
        }
    }
}
