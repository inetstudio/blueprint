<?php

namespace Packages\PagesPackage\Pages\Transformers\Front;

use Exception;
use League\Fractal\Resource\Item;
use InetStudio\AdminPanel\Base\Transformers\BaseTransformer;
use Illuminate\Contracts\Container\BindingResolutionException;
use InetStudio\PagesPackage\Pages\Contracts\Models\PageModelContract;

/**
 * Class ItemTransformer.
 */
final class ItemTransformer extends BaseTransformer
{
    /**
     * @var array
     */
    protected $defaultIncludes = [
        'meta',
    ];

    /**
     * @var array
     */
    protected $availableIncludes = [
        'objects',
    ];

    /**
     * Трансформация данных.
     *
     * @param  PageModelContract  $item
     *
     * @return array
     *
     * @throws Exception
     */
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

    /**
     * Включаем объекты в трансформацию.
     *
     * @param  PageModelContract  $item
     *
     * @return Item
     *
     * @throws BindingResolutionException
     */
    public function includeObjects(PageModelContract $item): Item
    {
        $transformer = $this->getTransformer('InetStudio\AdminPanel\Base\Transformers\Front\Objects\SelfTransformer');

        return $this->item($item, $transformer);
    }

    /**
     * Включаем мета-теги в трансформацию.
     *
     * @param  PageModelContract  $item
     *
     * @return Item
     *
     * @throws BindingResolutionException
     */
    public function includeMeta(PageModelContract $item): Item
    {
        $transformer = $this->getTransformer('Packages\MetaPackage\Meta\Transformers\Front\MetaTransformer');

        return $this->item($item, $transformer);
    }
}
