<?php

namespace Packages\PagesPackage\Pages\Http\Responses\Front;

use Illuminate\Http\Request;
use Illuminate\Contracts\Support\Responsable;
use InetStudio\PagesPackage\Pages\Contracts\Services\Front\ItemsServiceContract as PagesServiceContract;

/**
 * Class GetItemResponse.
 */
final class GetItemResponse implements Responsable
{
    /**
     * @var PagesServiceContract
     */
    protected PagesServiceContract $pagesService;

    /**
     * @var array
     */
    protected array $queryParams = [
        'columns' => ['content'],
        'relations' => ['meta', 'media'],
    ];

    /**
     * GetItemResponse constructor.
     *
     * @param  PagesServiceContract  $pagesService
     */
    public function __construct(PagesServiceContract $pagesService)
    {
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
        $slug = $request->route('slug');

        $item = $this->pagesService->getItemBySlug($slug, $this->queryParams);

        if (! $item) {
            abort(404);
        }

        $view = 'packages.pages.app::front.pages.';
        $view = (view()->exists($view.$slug)) ? $view.$slug : $view.'default';

        $data = [
            'SEO' => $item['meta'],
            'item' => $item,
        ];

        return view($view, $data);
    }
}
