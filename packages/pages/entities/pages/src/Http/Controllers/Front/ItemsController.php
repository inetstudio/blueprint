<?php

namespace Packages\PagesPackage\Pages\Http\Controllers\Front;

use Illuminate\Http\Request;
use InetStudio\AdminPanel\Base\Http\Controllers\Controller;
use Packages\PagesPackage\Pages\Http\Responses\Front\GetItemResponse;

final class ItemsController extends Controller
{
    public function getItem(Request $request, GetItemResponse $response): GetItemResponse
    {
        return $response;
    }
}
