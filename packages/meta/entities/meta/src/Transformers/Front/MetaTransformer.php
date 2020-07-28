<?php

namespace Packages\MetaPackage\Meta\Transformers\Front;

use Illuminate\Support\Arr;
use InetStudio\AdminPanel\Base\Transformers\BaseTransformer;
use InetStudio\MetaPackage\Meta\Contracts\Services\Front\ItemsServiceContract;

final class MetaTransformer extends BaseTransformer
{
    public ItemsServiceContract $metasService;

    public function __construct(ItemsServiceContract $metasService)
    {
        $this->metasService = $metasService;
    }

    public function transform($item): array
    {
        $metas = $this->metasService->getAllTags($item);

        $metaData = [];
        foreach ($metas as $key => $meta) {
            if ($meta) {
                if (! ($item->type === null && $key == 'canonical')) {
                    $metaData['html'][$key] = $meta->render();
                }

                switch ($key) {
                    case 'title':
                        $metaData['raw'][$key] = $meta->getTitleOnly();
                        break;
                }
            }
        }

        Arr::forget($metaData['html'], 'csrf-token');

        return $metaData;
    }
}
