<?php

namespace Packages\PagesPackage\Pages\Listeners\Front;

use Illuminate\Contracts\Container\BindingResolutionException;

/**
 * Class ClearItemCache.
 */
final class ClearItemCache
{
    /**
     * Handle the event.
     *
     * @param $event
     *
     * @throws BindingResolutionException
     */
    public function handle($event): void
    {
        $cacheService = app()->make('CacheService');

        $item = $event->item->fresh();

        $cacheService->clearCacheKeys($item);
    }
}
