<?php

namespace Packages\MainPagePackage\MainPage\Services\Front;

use InetStudio\CachePackage\Cache\Contracts\Services\Front\CacheServiceContract;

/**
 * Class MainPageService.
 */
final class MainPageService
{
    /**
     * @var CacheServiceContract
     */
    protected CacheServiceContract $cacheService;

    /**
     * MainPageService constructor.
     *
     * @param  CacheServiceContract  $cacheService
     */
    public function __construct(CacheServiceContract $cacheService) {
        $this->cacheService = $cacheService;
    }

    /**
     * * Получаем объекты главной страницы.
     *
     * @return array
     */
    public function getItems(): array
    {
        return [];
    }
}
