<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

/**
 * Class PagesController.
 */
class PagesController extends Controller
{
    /**
     * Получаем страницу статичного материала.
     *
     * @param string $slug
     *
     * @return \Illuminate\Http\Response
     */
    public function getPage(string $slug = 'index')
    {
        $pagesService = app()->make('InetStudio\Pages\Contracts\Services\Front\PagesServiceContract');
        $seoService = app()->make('InetStudio\Meta\Contracts\Services\Front\MetaServiceContract');

        $cacheKey = 'pagesService_getItemBySlug_'.md5($slug);
        $page = Cache::remember($cacheKey, now()->addDays(100), function () use ($pagesService, $slug) {
            return $pagesService->getItemBySlug($slug);
        });

        if (! $page) {
            abort(404);
        }

        $view = 'front.pages.'.$slug;

        return response()->view($view, [
            'SEO' => $seoService->getAllTags($page),
            'item' => $page,
        ]);
    }
}
