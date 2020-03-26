<?php

namespace Packages\MetaPackage\Meta\Transformers\Front;

use Illuminate\Support\Arr;
use InetStudio\AdminPanel\Base\Transformers\BaseTransformer;

/**
 * Class MetaTransformer.
 */
final class MetaTransformer extends BaseTransformer
{
    /**
     * Используемые сервисы.
     *
     * @var array
     */
    public array $services = [];

    /**
     * MetaTransformer constructor.
     */
    public function __construct()
    {
        $this->services['seo'] = app()->make('InetStudio\MetaPackage\Meta\Contracts\Services\Front\ItemsServiceContract');
    }

    /**
     * Трансформация данных.
     *
     * @param $item
     *
     * @return array
     */
    public function transform($item): array
    {
        $metas = $this->services['seo']->getAllTags($item);

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
