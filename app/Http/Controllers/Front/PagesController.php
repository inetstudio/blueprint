<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use InetStudio\PagesPackage\Pages\Contracts\Services\Front\ItemsServiceContract as PagesServiceContract;

/**
 * Class PagesController.
 */
class PagesController extends Controller
{
    /**
     * Получаем страницу статичного материала.
     *
     * @param  PagesServiceContract  $pagesService
     * @param  string  $slug
     *
     * @return Response
     */
    public function getPage(PagesServiceContract $pagesService, string $slug = 'index'): Response
    {
        $item = $pagesService->getItemBySlug($slug);

        $view = 'front.pages.'.$slug;

        return response()->view($view, [
            'SEO' => $item['meta'],
            'item' => $item,
        ]);
    }
}
