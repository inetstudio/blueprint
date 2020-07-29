<?php

namespace Packages\PagesPackage\Pages\Transformers\Front;

use League\Fractal\Resource\Item;
use InetStudio\AdminPanel\Base\Transformers\BaseTransformer;
use InetStudio\PagesPackage\Pages\Contracts\Models\PageModelContract;

final class ItemTransformer extends BaseTransformer
{
    protected $defaultIncludes = [
        'meta',
    ];

    protected $availableIncludes = [
        'objects',
    ];

    public function transform(PageModelContract $item): array
    {
        return [
            'id' => (int) $item['id'],
            'title' => $item['title'],
            'slug' => $item['slug'],
            'href' => $item['href'],
            'content' => blade_string($item['content']),
        ];
    }

    public function includeObjects(PageModelContract $item): Item
    {
        $transformer = $this->getTransformer('InetStudio\AdminPanel\Base\Transformers\Front\Objects\SelfTransformer');

        return $this->item($item, $transformer);
    }

    public function includeMeta(PageModelContract $item): Item
    {
        $transformer = $this->getTransformer('Packages\MetaPackage\Meta\Transformers\Front\MetaTransformer');

        return $this->item($item, $transformer);
    }
}
