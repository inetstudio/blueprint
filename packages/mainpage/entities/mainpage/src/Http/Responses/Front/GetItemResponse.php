<?php

namespace Packages\MainPagePackage\MainPage\Http\Responses\Front;

use Illuminate\Contracts\Support\Responsable;
use Packages\MainPagePackage\MainPage\Services\Front\MainPageService;
use InetStudio\PagesPackage\Pages\Contracts\Services\Front\ItemsServiceContract as PagesServiceContract;
use InetStudio\ReceiptsContest\Receipts\Contracts\Services\Front\ItemsServiceContract as ReceiptsServiceContract;

final class GetItemResponse implements Responsable
{
    protected MainPageService $mainPageService;

    protected PagesServiceContract $pagesService;

    protected ReceiptsServiceContract $receiptsService;

    public function __construct(
        MainPageService $mainPageService,
        PagesServiceContract $pagesService,
        ReceiptsServiceContract $receiptsService
    ) {
        $this->mainPageService = $mainPageService;
        $this->pagesService = $pagesService;
        $this->receiptsService = $receiptsService;
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
        $stages = $this->receiptsService->getContestStages();

        $data = array_merge(
            $mainPageItems,
            [
                'SEO' => $indexPage['meta'],
                'item' => $indexPage,
                'stages' => $stages,
            ]
        );

        return view('packages.mainpage.app::front.pages.index', $data);
    }
}
