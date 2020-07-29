<?php

namespace Packages\PagesPackage\Pages\Services\Front;

use Illuminate\Support\Facades\Cache;
use InetStudio\PagesPackage\Pages\Contracts\Models\PageModelContract;
use InetStudio\CachePackage\Cache\Contracts\Services\Front\CacheServiceContract;
use InetStudio\PagesPackage\Pages\Services\Front\SitemapService as PackageSitemapService;

final class SitemapService extends PackageSitemapService
{
    protected CacheServiceContract $cacheService;

    public function __construct(CacheServiceContract $cacheService, PageModelContract $model)
    {
        parent::__construct($model);

        $this->cacheService = $cacheService;
    }

    public function getItems(array $params = []): array
    {
        $cacheKey = $this->cacheService->generateCacheKey();

        $cacheCallback = function () use ($cacheKey, $params) {
            $defaultParams = [
                'columns' => ['created_at', 'updated_at'],
                'order' => ['created_at' => 'desc'],
            ];

            $items = $this->model->buildQuery(array_merge($defaultParams, $params))->get();

            return $this->cacheService
                ->init('InetStudio\PagesPackage\Pages\Contracts\Transformers\Front\Sitemap\ItemTransformerContract', $cacheKey, array_merge($defaultParams, $params))
                ->addKeysToCacheGroup($this->cacheService->generateCacheKey(true, '', 5), $cacheKey)
                ->cacheItems($items, true);
        };

        $itemsKeys = Cache::remember($cacheKey, now()->addDays(1), $cacheCallback);
        $items = $this->cacheService->getCachedItems($itemsKeys);

        return $items->toArray();
    }
}
