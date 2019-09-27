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
        $cacheService = app()->make('InetStudio\CachePackage\Cache\Contracts\Services\Front\CacheServiceContract');
        $pagesService = app()->make('InetStudio\PagesPackage\Pages\Contracts\Services\Front\ItemsServiceContract');

        $item = $event->item->fresh();

        $cacheService->clearCacheKeys($item);

        $groupKeys = [
            $cacheService->getCacheKeyByClassAndMethod($pagesService, 'getItemBySlug', [], true, $item['slug']),
        ];

        foreach ($groupKeys as $groupKey) {
            $cacheService->clearCacheGroup($groupKey);
        }
    }
}
