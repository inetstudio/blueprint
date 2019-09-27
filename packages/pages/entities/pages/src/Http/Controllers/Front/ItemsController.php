<?php

namespace Packages\PagesPackage\Pages\Http\Controllers\Front;

use Illuminate\Http\Request;
use InetStudio\AdminPanel\Base\Http\Controllers\Controller;
use Packages\PagesPackage\Pages\Http\Responses\Front\GetItemResponse;

/**
 * Class ItemsController.
 */
final class ItemsController extends Controller
{
    /**
     * Получаем страницу статичного материала.
     *
     * @param  GetItemResponse  $response
     *
     * @return GetItemResponse
     */
    public function getItem(GetItemResponse $response): GetItemResponse
    {
        return $response;
    }

    /**
     * Возвращаем страницу обратной связи.
     *
     * @param  Request  $request
     * @param  GetItemResponse  $response
     *
     * @return GetItemResponse
     */
    public function getFeedback(Request $request, GetItemResponse $response): GetItemResponse
    {
        $request->route()->setParameter('slug',  'feedback');

        return $response;
    }

    /**
     * Получаем промо страницу.
     *
     * @param  Request  $request
     * @param  GetItemResponse  $response
     *
     * @return GetItemResponse
     */
    public function getPromoItem(Request $request, GetItemResponse $response): GetItemResponse
    {
        $request->merge(
            [
                'promo' => true,
            ]
        );

        return $response;
    }
}
