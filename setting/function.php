<?php

use App\Repository\CategoryRepository;
use Cycle\ORM\ORMInterface;
use Module\View\Support\ViewDataFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Takemo101\Egg\Routing\RouteBuilder;
use Takemo101\Egg\Support\Hook\Hook;
use Takemo101\Egg\Support\StaticContainer;

/** @var Hook */
$hook = StaticContainer::get('hook');

$hook->register(
    RouteBuilder::class,
    function (RouteBuilder $r) {
        $r->get('/phpinfo', function (Response $response) {
            phpinfo();
        })
            ->name('phpinfo');

        return $r;
    },
);

// リクエストのフックによる強制https化
$hook->register(
    Request::class,
    function (Request $r) {
        if (config('setting.force_https', false)) {
            $r->server->set('HTTPS', 'on');
            $r->server->set('SSL', 'on');
            $r->server->set('HTTP_X_FORWARDED_PROTO', 'https');
            $r->server->set('HTTP_X_FORWARDED_PORT', '443');
            $r->server->set('SERVER_PORT', '443');
        }

        return $r;
    },
);

// Viewの共有データへのフック
$hook->register(
    ViewDataFactory::class,
    function (ViewDataFactory $factory) {
        $factory->addHandler('categories', function (ORMInterface $orm) {
            /** @var CategoryRepository */
            $repository = $orm->getRepository('category');

            return $repository->findAll();
        });

        return $factory;
    },
);
