<?php

namespace App\Transformers\Front\Objects;

use App\Transformers\BaseTransformer;

/**
 * Class SelfTransformer.
 */
final class SelfTransformer extends BaseTransformer
{
    /**
     * Подготовка данных для объектов.
     *
     * @param $item
     *
     * @return array
     */
    public function transform($item): array
    {
        $emptyItem = $item->newInstance([], true);
        $emptyItem->id = $item->id;

        return [
            'empty' => $emptyItem,
        ];
    }
}
