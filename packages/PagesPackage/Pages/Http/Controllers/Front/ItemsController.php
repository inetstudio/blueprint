<?php

namespace Packages\PagesPackage\Pages\Http\Controllers\Front;

use InetStudio\AdminPanel\Base\Http\Controllers\Controller;
use Packages\PagesPackage\Pages\Http\Responses\Front\PageResponse;
use InetStudio\PagesPackage\Pages\Contracts\Services\Front\ItemsServiceContract as PagesServiceContract;

/**
 * Class ItemsController.
 */
final class ItemsController extends Controller
{
    /**
     * Получаем страницу статичного материала.
     *
     * @param  PagesServiceContract  $pagesService
     *
     * @return PageResponse
     */
    public function getIndex(PagesServiceContract $pagesService): PageResponse
    {
        $item = $pagesService->getItemBySlug('index');

        return new PageResponse('index', [
            'SEO' => $item['meta'],
            'item' => $item,
        ]);
    }
}
