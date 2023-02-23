<?php

use Takemo101\Egg\Support\Injector\ContainerContract;
use Microcms\Client;

return function (ContainerContract $c) {
    $singletons = [
        // MicroCMSのクライアント
        Client::class => fn () => new Client(
            config('setting.microcms.domain'),
            config('setting.microcms.api-key'),
        ),
    ];

    foreach ($singletons as $abstract => $class) {
        $c->singleton($abstract, $class);
    }
};
