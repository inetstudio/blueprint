<?php

namespace Packages\PagesPackage\Pages\Services\Front;

use Illuminate\Support\Facades\Cache;
use InetStudio\PagesPackage\Pages\Contracts\Models\PageModelContract;
use InetStudio\CachePackage\Cache\Contracts\Services\Front\CacheServiceContract;
use InetStudio\PagesPackage\Pages\Services\Front\ItemsService as PackageItemsService;

final class ItemsService extends PackageItemsService
{
    protected CacheServiceContract $cacheService;

    public function __construct(CacheServiceContract $cacheService, PageModelContract $model)
    {
        parent::__construct($model);

        $this->cacheService = $cacheService;
    }

    public function getItemBySlug(string $slug, array $params = [])
    {
        $cacheKey = $this->cacheService->generateCacheKey();

        $cacheCallback = function () use ($cacheKey, $slug, $params) {
            $item = parent::getItemBySlug($slug, $params)->first();

            $this->cacheService->addKeysToCacheGroup($this->cacheService->generateCacheKey(true, $slug, 5), $cacheKey);

            if (! $item) {
                return collect([]);
            }

            return $this->cacheService
                ->init('Packages\PagesPackage\Pages\Transformers\Front\ItemTransformer', $cacheKey, $params)
                ->cacheItems($item, true);
        };

        $itemsKeys = Cache::remember($cacheKey, now()->addDays(1), $cacheCallback);

        return $this->cacheService->getCachedItems($itemsKeys)->first();
    }
}
