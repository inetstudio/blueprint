<?php

namespace Packages\PagesPackage\Pages\Listeners\Front;

final class ClearItemCache
{
    public function handle($event): void
    {
        $cacheService = resolve('InetStudio\CachePackage\Cache\Contracts\Services\Front\CacheServiceContract');
        $pagesService = resolve('InetStudio\PagesPackage\Pages\Contracts\Services\Front\ItemsServiceContract');

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
