<?php

namespace Packages\PagesPackage\Pages\Transformers\Front;

use Exception;
use League\Fractal\Resource\Item;
use App\Transformers\BaseTransformer;
use App\Transformers\Front\Objects\SelfTransformer;
use Packages\MetaPackage\Meta\Transformers\Front\MetaTransformer;
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
     * Подготовка данных для отображения в материале.
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
     * Включаем мета-теги в трансформацию.
     *
     * @param  PageModelContract  $item
     *
     * @return Item
     */
    public function includeMeta(PageModelContract $item): Item
    {
        return $this->item($item, new MetaTransformer());
    }

    /**
     * Включаем объекты в трансформацию.
     *
     * @param  PageModelContract  $item
     *
     * @return Item
     */
    public function includeObjects(PageModelContract $item): Item
    {
        return $this->item($item, new SelfTransformer());
    }
}
