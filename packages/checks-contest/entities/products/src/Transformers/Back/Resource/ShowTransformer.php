<?php

namespace Packages\ChecksContest\Products\Transformers\Back\Resource;

use InetStudio\ChecksContest\Products\Contracts\Models\ProductModelContract;
use InetStudio\ChecksContest\Products\Transformers\Back\Resource\ShowTransformer as PackageShowTransformer;

/**
 * Class ShowTransformer.
 */
class ShowTransformer extends PackageShowTransformer
{
    /**
     * Подготовка данных для отображения в таблице.
     *
     * @param  ProductModelContract  $item
     *
     * @return array
     */
    public function transform(ProductModelContract $item): array
    {
        $data = $item->toArray();
        
        $data['highlight'] = 
            (mb_strpos(mb_strtolower($item['name']), 'garnier') !== false && mb_strpos(mb_strtolower($item['name']), 'чист') !== false && mb_strpos(mb_strtolower($item['name']), 'кож') !== false) ||
            (mb_strpos(mb_strtolower($item['name']), 'garnier') !== false && mb_strpos(mb_strtolower($item['name']), 'очищ') !== false && mb_strpos(mb_strtolower($item['name']), 'ср-во') !== false);

        return $data;
    }
}
