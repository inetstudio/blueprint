<?php

namespace App\Services\Front\PagesPackage\Pages;

use Illuminate\Support\Facades\Cache;
use App\Transformers\Front\PagesPackage\Pages\PageTransformer;
use InetStudio\PagesPackage\Pages\Contracts\Services\Front\ItemsServiceContract;
use InetStudio\PagesPackage\Pages\Services\Front\ItemsService as PackageItemsService;

/**
 * Class ItemsService.
 */
final class ItemsService extends PackageItemsService implements ItemsServiceContract
{
    /**
     * Получаем объект по slug.
     *
     * @param  string  $slug
     * @param  array  $params
     *
     * @return mixed
     */
    public function getItemBySlug(string $slug, array $params = [])
    {
        $arguments = json_encode(func_get_args());
        $cacheKey = 'pagesService_getItemBySlug_'.md5($arguments);

        $cacheCallback = function () use ($cacheKey, $slug, $params) {
            $defaultParams = [
                'columns' => ['content'],
                'relations' => ['meta', 'media'],
            ];

            $item = parent::getItemBySlug($slug, array_merge($defaultParams, $params))->first();

            if (! $item) {
                return [];
            }

            return app()->make('CacheService')
                ->cacheItems(
                    collect([$item]),
                    (new PageTransformer()),
                    ['branding'],
                    [$cacheKey]
                )->first();
        };

        $item = Cache::remember($cacheKey, now()->addDays(100), $cacheCallback);

        if (empty($item)) {
            abort(404);
        }

        return $item;
    }
}
