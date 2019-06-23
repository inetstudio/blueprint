<?php

namespace Packages\PagesPackage\Pages\Listeners\Back;

use Illuminate\Contracts\Container\BindingResolutionException;

/**
 * Class ClearItemsCache.
 */
final class ClearItemsCache
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
