<?php

use App\Http\Controller\BlogController;
use App\Http\Controller\CategoryController;
use App\Http\Controller\HomeController;
use Takemo101\Egg\Routing\RouteBuilder;

return function (RouteBuilder $r) {
    $r->get('/', [HomeController::class, 'home'])
        ->name('home');

    $r->group(function (RouteBuilder $r) {
        $r->get('/', [BlogController::class, 'index'])
            ->name('index');
        $r->get('/[s:id]', [BlogController::class, 'show'])
            ->name('show');
    })
        ->path('/blog')
        ->name('blog.');

    $r->get('/category/[s:id]', [CategoryController::class, 'show'])
        ->name('category.show');
};
