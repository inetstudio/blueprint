<?php

namespace Packages\MainPagePackage\MainPage\Http\Responses\Front;

use Illuminate\Http\Request;
use Illuminate\Contracts\Support\Responsable;
use Packages\MainPagePackage\MainPage\Services\Front\MainPageService;
use InetStudio\PagesPackage\Pages\Contracts\Services\Front\ItemsServiceContract as PagesServiceContract;

/**
 * Class GetItemResponse.
 */
final class GetItemResponse implements Responsable
{
    /**
     * @var MainPageService
     */
    protected MainPageService $mainPageService;

    /**
     * @var PagesServiceContract
     */
    protected PagesServiceContract $pagesService;

    /**
     * GetItemResponse constructor.
     *
     * @param  MainPageService  $mainPageService
     * @param  PagesServiceContract  $pagesService
     */
    public function __construct(
        MainPageService $mainPageService,
        PagesServiceContract $pagesService
    ) {
        $this->mainPageService = $mainPageService;
        $this->pagesService = $pagesService;
    }

    /**
     * Возвращаем ответ.
     *
     * @param  Request  $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
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
