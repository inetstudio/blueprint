<?php

namespace Packages\PagesPackage\Pages\Http\Responses\Front;

use Illuminate\Contracts\Support\Responsable;
use InetStudio\PagesPackage\Pages\Contracts\Services\Front\ItemsServiceContract as PagesServiceContract;

final class GetItemResponse implements Responsable
{
    protected PagesServiceContract $pagesService;

    protected array $queryParams = [
        'columns' => ['content'],
        'relations' => ['meta', 'media'],
    ];

    public function __construct(PagesServiceContract $pagesService)
    {
        $this->pagesService = $pagesService;
    }

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
