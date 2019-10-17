<?php

namespace Packages\MainPagePackage\MainPage\Http\Responses\Front;

use InetStudio\AdminPanel\Base\Http\Responses\BaseResponse;
use Packages\MainPagePackage\MainPage\Services\Front\MainPageService;
use InetStudio\PagesPackage\Pages\Contracts\Services\Front\ItemsServiceContract as PagesServiceContract;
use InetStudio\ChecksContest\Checks\Contracts\Services\Front\ItemsServiceContract as ChecksServiceContract;

/**
 * Class GetItemResponse.
 */
final class GetItemResponse extends BaseResponse
{
    /**
     * @var MainPageService
     */
    protected $mainPageService;

    /**
     * @var PagesServiceContract
     */
    protected $pagesService;

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

        $this->abortOnEmptyData = true;
        $this->view = 'packages.mainpage.app::front.pages.index';
    }

    /**
     * Prepare response data.
     *
     * @param $request
     *
     * @return array
     */
    protected function prepare($request): array
    {
        $indexPage = $this->pagesService->getItemBySlug(
            'index',
            [
                'columns' => ['content'],
                'relations' => ['meta', 'media'],
            ]
        );

        if (! $indexPage) {
            return [];
        }

        $mainPageItems = $this->mainPageService->getItems();
        $stages = $this->checksService->getContestStages();

        return array_merge(
            $mainPageItems,
            [
                'SEO' => $indexPage['meta'],
                'item' => $indexPage,
                'stages' => $stages,
            ]
        );
    }
}
