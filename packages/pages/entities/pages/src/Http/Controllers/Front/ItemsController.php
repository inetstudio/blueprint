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
     * @param  Request  $request
     * @param  GetItemResponse  $response
     *
     * @return GetItemResponse
     */
    public function getItem(Request $request, GetItemResponse $response): GetItemResponse
    {
        return $response;
    }
}
