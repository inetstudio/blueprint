<?php

namespace Packages\MainPagePackage\MainPage\Http\Responses\Front;

use InetStudio\AdminPanel\Base\Http\Responses\BaseResponse;
use Packages\MainPagePackage\MainPage\Services\Front\MainPageService;
use InetStudio\PagesPackage\Pages\Contracts\Services\Front\ItemsServiceContract as PagesServiceContract;

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
                'relations' => ['meta', 'media', 'custom_fields'],
                'includes' => ['branding'],
            ]
        );

        if (! $indexPage) {
            return [];
        }

        $mainPageItems = $this->mainPageService->getItems();

        return array_merge(
            $mainPageItems,
            [
                'SEO' => $indexPage['meta'],
                'item' => $indexPage,
            ]
        );
    }
}
