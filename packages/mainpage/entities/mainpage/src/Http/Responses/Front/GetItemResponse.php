<?php

namespace Packages\MainPagePackage\MainPage\Http\Responses\Front;

use Illuminate\Http\Request;
use Illuminate\Contracts\Support\Responsable;
use Packages\MainPagePackage\MainPage\Services\Front\MainPageService;
use InetStudio\PagesPackage\Pages\Contracts\Services\Front\ItemsServiceContract as PagesServiceContract;
use InetStudio\ChecksContest\Checks\Contracts\Services\Front\ItemsServiceContract as ChecksServiceContract;

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
     * @var ChecksServiceContract
     */
    protected $checksService;

    /**
     * GetItemResponse constructor.
     *
     * @param  MainPageService  $mainPageService
     * @param  PagesServiceContract  $pagesService
     * @param  ChecksServiceContract  $checksService
     */
    public function __construct(
        MainPageService $mainPageService,
        PagesServiceContract $pagesService,
        ChecksServiceContract $checksService
    ) {
        $this->mainPageService = $mainPageService;
        $this->pagesService = $pagesService;
        $this->checksService = $checksService;
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
        $stages = $this->checksService->getContestStages();

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
