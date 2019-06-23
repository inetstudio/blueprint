<?php

namespace Packages\CachePackage\Cache\Services\Front\Cache;

use League\Fractal\Manager;
use Illuminate\Cache\FileStore;
use Illuminate\Cache\RedisStore;
use League\Fractal\Resource\Item;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use League\Fractal\TransformerAbstract;
use Illuminate\Contracts\Container\BindingResolutionException;

/**
 * Trait CacheService.
 */
final class CacheService
{
    /**
     * @var Manager
     */
    protected $manager;

    /**
     * @var
     */
    protected $cacheStore;

    /**
     * CacheService constructor.
     */
    public function __construct()
    {
        $this->cacheStore = Cache::getStore();
    }

    /**
     * Кэшируем результаты запросов.
     *
     * @param  Collection  $items
     * @param  TransformerAbstract  $transformer
     * @param  array  $includes
     * @param  array  $additionalCacheKeys
     *
     * @return Collection
     */
    public function cacheItems(
        Collection $items,
        TransformerAbstract $transformer,
        array $includes = [],
        array $additionalCacheKeys = []
    ): Collection {
        $data = [];

        $transformCacheKey = md5(get_class($transformer).json_encode($includes));

        foreach ($items as $item) {
            if ($item) {
                $objectKey = md5(get_class($item).$item->id);

                $cacheKey = 'transform_'.$transformCacheKey.'_'.$objectKey;

                $groupCacheKey = 'cacheKeys_'.$objectKey;

                $cacheKeys = array_merge([$cacheKey], $additionalCacheKeys);

                $this->addKeysToCacheGroup($groupCacheKey, $cacheKeys);
                $transformer->addCacheKeys($cacheKeys);

                $data[] = Cache::rememberForever($cacheKey, function () use ($item, $transformer, $includes) {
                    $resource = new Item($item, $transformer);

                    return $this->getManager($includes)->createData($resource)->toArray();
                });
            }
        }

        return collect($data);
    }

    /**
     * Добавляем ключи в группу.
     *
     * @param  string  $groupKey
     * @param  array  $additionalCacheKeys
     */
    public function addKeysToCacheGroup(string $groupKey, array $additionalCacheKeys): void
    {
        if (empty($additionalCacheKeys)) {
            return;
        }

        $keys = [];

        if ($this->cacheStore instanceof FileStore) {
            $keys = Cache::get($groupKey, []);
        }

        if (empty(array_diff($additionalCacheKeys, $keys))) {
            return;
        }

        $keys = array_unique(array_merge($keys, $additionalCacheKeys));

        if ($this->cacheStore instanceof RedisStore) {
            foreach ($keys as $key) {
                Cache::tags([$groupKey])->forever($key, $key);
            }
        } elseif ($this->cacheStore instanceof FileStore) {
            Cache::forget($groupKey);
            Cache::forever($groupKey, $keys);
        }
    }

    /**
     * Очищаем кэш по ключам.
     *
     * @param $item
     */
    public function clearCacheKeys($item): void
    {
        if ($item) {
            $cacheKey = 'cacheKeys_'.md5(get_class($item).$item->id);

            $this->clearCacheGroup($cacheKey);
        }
    }

    /**
     * Очищаем кэш по группе ключей.
     *
     * @param  string  $groupKey
     */
    public function clearCacheGroup(string $groupKey): void
    {
        if ($this->cacheStore instanceof RedisStore) {
            $prefix = $this->cacheStore->getPrefix();

            $tagKey = 'tag:'.$groupKey.':key';
            $setKey = Cache::get($tagKey);
            $setMembersKeys = $this->cacheStore->connection()->smembers($prefix.$setKey.':forever_ref');

            foreach ($setMembersKeys as $setMemberKey) {
                $setMemberKey = str_replace($prefix, '', $setMemberKey);
                $cacheKey = Cache::get($setMemberKey);

                Cache::forget($cacheKey);
            }

            Cache::forget($setKey);
            Cache::forget($tagKey);
            Cache::tags([$groupKey])->flush();
        } elseif ($this->cacheStore instanceof FileStore) {
            $keys = Cache::get($groupKey, []);

            foreach ($keys as $key) {
                Cache::forget($key);
            }

            Cache::forget($groupKey);
        }
    }

    /**
     * Инициализируем менеджера трансформации.
     *
     * @param  array  $includes
     *
     * @return Manager
     *
     * @throws BindingResolutionException
     */
    protected function getManager(array $includes = [])
    {
        if (! $this->manager) {
            $serializer = app()->make('InetStudio\AdminPanel\Base\Contracts\Serializers\SimpleDataArraySerializerContract');

            $this->manager = new Manager();
            $this->manager->setSerializer($serializer);
            $this->manager->parseIncludes($includes);
        }

        return $this->manager;
    }

    /**
     * Возвращаем ключи группы.
     *
     * @param  string  $groupKey
     *
     * @return array
     */
    public function getGroupCacheKeys(string $groupKey = ''): array
    {
        $keys = [];

        if ($this->cacheStore instanceof RedisStore) {
            $prefix = $this->cacheStore->getPrefix();

            $tagKey = 'tag:'.$groupKey.':key';
            $setKey = Cache::get($tagKey);
            $setMembersKeys = $this->cacheStore->connection()->smembers($prefix.$setKey.':forever_ref');

            foreach ($setMembersKeys as $setMemberKey) {
                $setMemberKey = str_replace($prefix, '', $setMemberKey);

                $keys[] = Cache::get($setMemberKey);
            }
        } elseif ($this->cacheStore instanceof FileStore) {
            $keys = Cache::get($groupKey, []);
        }

        return $keys;
    }
}
