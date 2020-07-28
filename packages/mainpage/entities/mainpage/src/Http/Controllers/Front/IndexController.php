<?php

namespace Packages\MainPagePackage\MainPage\Http\Controllers\Front;

use InetStudio\AdminPanel\Base\Http\Controllers\Controller;
use Packages\MainPagePackage\MainPage\Http\Responses\Front\GetItemResponse;

final class IndexController extends Controller
{
    public function getItem(GetItemResponse $response): GetItemResponse
    {
        return $response;
    }
}
