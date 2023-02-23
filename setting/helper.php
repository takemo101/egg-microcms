<?php

use Takemo101\Egg\Kernel\Application;
use Takemo101\Egg\Support\StaticContainer;
use Takemo101\Egg\Http\Filter\CsrfFilter;
use Takemo101\Egg\Support\Filesystem\URLHelper;

if (!function_exists('csrf_token')) {
    /**
     * Csrfトークン取得
     *
     * @return string
     */
    function csrf_token(): string
    {
        /** @var Application */
        $app = StaticContainer::get('app');

        /** @var CsrfFilter */
        $filter = $app->container->make(CsrfFilter::class);

        return $filter->token();
    }
}

if (!function_exists('url')) {
    /**
     * url取得
     *
     * @param string|null $path
     * @return string
     */
    function url(?string $path = null): string
    {
        $helper = new URLHelper();

        return $helper->join(
            config('app.url', '/'),
            $path,
        );
    }
}

if (!function_exists('asset')) {
    /**
     * assetパス取得
     *
     * @param string|null $path
     * @return string
     */
    function asset(?string $path = null): string
    {
        return url($path);
    }
}
