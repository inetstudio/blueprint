<?php

namespace Packages\MainPagePackage\MainPage\Services\Front;

use InetStudio\CachePackage\Cache\Contracts\Services\Front\CacheServiceContract;

final class MainPageService
{
    protected CacheServiceContract $cacheService;

    public function __construct(CacheServiceContract $cacheService) {
        $this->cacheService = $cacheService;
    }

    public function getItems(): array
    {
        return [];
    }
}
