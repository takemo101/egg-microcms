<?php

use Module\View\Latte\LatteViewGenerator;
use Module\View\Session\FlashErrorMessages;
use Takemo101\Egg\Kernel\Application;
use Takemo101\Egg\Support\StaticContainer;
use Module\View\Session\FlashOldInputs;

if (!function_exists('latte')) {
    /**
     * Latteでテンプレートをレンダリングしてレスポンスを返す
     *
     * @param string $path
     * @param object|mixed[] $params
     * @param string|null $block
     * @return string
     */
    function latte(string $path, object|array $params = [], ?string $block = null): string
    {
        /** @var Application */
        $app = StaticContainer::get('app');

        /** @var LatteViewGenerator */
        $latte = $app->container->make(LatteViewGenerator::class);

        return $latte->generate($path, $params, $block);
    }
}

if (!function_exists('old')) {
    /**
     * 前の入力値
     *
     * @return mixed
     */
    function old(?string $key = null, mixed $default = null)
    {
        /** @var Application */
        $app = StaticContainer::get('app');

        /** @var FlashOldInputs */
        $inputs = $app->container->make(FlashOldInputs::class);

        return $key
            ? $inputs->get($key, $default)
            : $inputs;
    }
}

if (!function_exists('errors')) {
    /**
     * 前の入力値
     *
     * @return mixed
     */
    function errors(?string $key = null)
    {
        /** @var Application */
        $app = StaticContainer::get('app');

        /** @var FlashErrorMessages */
        $errors = $app->container->make(FlashErrorMessages::class);

        return $key
            ? $errors->first($key)
            : $errors;
    }
}
