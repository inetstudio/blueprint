<?php

namespace Packages\MainPagePackage\MainPage\Http\Controllers\Front;

use InetStudio\AdminPanel\Base\Http\Controllers\Controller;
use Packages\MainPagePackage\MainPage\Http\Responses\Front\GetItemResponse;
use Packages\MainPagePackage\MainPage\Http\Responses\Front\GetSocialWidgetResponse;

/**
 * Class IndexController.
 */
final class IndexController extends Controller
{
    /**
     * Получаем главную страницу.
     *
     * @param  GetItemResponse  $response
     *
     * @return GetItemResponse
     */
    public function getItem(GetItemResponse $response): GetItemResponse
    {
        return $response;
    }
}
