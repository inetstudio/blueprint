<?php

namespace Packages\PagesPackage\Pages\Http\Responses\Front;

use InetStudio\AdminPanel\Base\Http\Responses\BaseResponse;
use InetStudio\PagesPackage\Pages\Contracts\Services\Front\ItemsServiceContract as PagesServiceContract;

/**
 * Class GetItemResponse.
 */
final class GetItemResponse extends BaseResponse
{
    /**
     * @var PagesServiceContract
     */
    protected $pagesService;

    /**
     * @var array
     */
    protected $queryParams = [
        'columns' => ['content'],
        'relations' => ['meta', 'media', 'custom_fields'],
        'includes' => ['branding'],
    ];

    /**
     * GetItemResponse constructor.
     *
     * @param  PagesServiceContract  $pagesService
     */
    public function __construct(PagesServiceContract $pagesService)
    {
        $this->pagesService = $pagesService;

        $this->abortOnEmptyData = true;
        $this->view = 'packages.pages.app::front.pages.';
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
        $slug = $request->route('slug');

        $item = $this->pagesService->getItemBySlug($slug, $this->queryParams);

        if (! $item) {
            return [];
        }

        $isPromo = $request->get('promo', false);

        if ($slug == 'policy') {
            $rules = $this->pagesService->getItemBySlug('rules', $this->queryParams);

            if (! $rules) {
                return [];
            }

            $view = 'policy';

            $data = [
                'SEO' => $item['meta'],
                'items' => [
                    'policy' => $item,
                    'rules' => $rules,
                ],
            ];
        } else {
            if ($isPromo && ! view()->exists($this->view.'promo.'.$slug)) {
                return [];
            }

            if ($isPromo) {
                $view = 'promo.'.$slug;
            } else {
                $view = (view()->exists($this->view.$slug)) ? $slug : 'default';
            }

            $data = [
                'SEO' => $item['meta'],
                'item' => $item,
            ];
        }

        $this->view .= $view;

        return $data;
    }
}
