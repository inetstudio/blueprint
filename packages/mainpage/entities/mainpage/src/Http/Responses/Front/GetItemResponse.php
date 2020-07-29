<?php

namespace Packages\MainPagePackage\MainPage\Http\Responses\Front;

use Illuminate\Contracts\Support\Responsable;
use Packages\MainPagePackage\MainPage\Services\Front\MainPageService;
use InetStudio\PagesPackage\Pages\Contracts\Services\Front\ItemsServiceContract as PagesServiceContract;

final class GetItemResponse implements Responsable
{
    protected MainPageService $mainPageService;

    protected PagesServiceContract $pagesService;

    public function __construct(
        MainPageService $mainPageService,
        PagesServiceContract $pagesService
    ) {
        $this->mainPageService = $mainPageService;
        $this->pagesService = $pagesService;
    }

    public function toResponse($request)
    {
        $indexPage = $this->pagesService->getItemBySlug(
            'index',
            [
                'columns' => ['content'],
                'relations' => ['meta', 'media'],
            ]
        );

        if (! $indexPage) {
            abort(404);
        }

        $mainPageItems = $this->mainPageService->getItems();

        $data = array_merge(
            $mainPageItems,
            [
                'SEO' => $indexPage['meta'],
                'item' => $indexPage,
            ]
        );

        return view('packages.mainpage.app::front.pages.index', $data);
    }
}
