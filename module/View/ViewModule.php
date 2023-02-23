<?php

namespace Module\View;

use Module\View\ErrorHandler\HttpErrorHandler;
use Module\View\Latte\LatteFileLoader;
use Module\View\Path\ResourcePath;
use Module\View\Session\FlashErrorMessages;
use Module\View\Session\FlashOldInputs;
use Takemo101\Egg\Module\Module;
use Takemo101\Egg\Support\Injector\ContainerContract;
use Latte\Engine as Latte;
use Module\View\Command\ViewClearCommand;
use Module\View\Latte\LatteViewGenerator;
use Module\View\Support\ViewDataFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Takemo101\Egg\Console\Commands;
use Takemo101\Egg\Http\HttpErrorHandlerContract;
use Takemo101\Egg\Kernel\ApplicationEnvironment;
use Takemo101\Egg\Kernel\ApplicationPath;
use Takemo101\Egg\Support\Log\Loggers;

final class ViewModule extends Module
{
    /**
     * モジュールを起動する
     *
     * @return void
     */
    public function boot(): void
    {
        // ヘルパー関数の読み込み
        require __DIR__ . '/helper.php';

        $singletons = [
            // テンプレートエンジン
            Latte::class => function (ContainerContract $c) {

                /** @var ApplicationPath */
                $applicationPath = $c->make(ApplicationPath::class);

                /** @var ResourcePath */
                $resourcePath = $c->make(ResourcePath::class);

                $latte = new Latte();

                $latte->setTempDirectory($applicationPath->storagePath(
                    config('setting.latte-cache-path', 'cache/latte')
                ));
                $latte->setLoader(
                    new LatteFileLoader(
                        $resourcePath->lattePath(),
                    ),
                );

                return $latte;
            },

            // 共有データ
            ViewDataFactory::class => fn (ContainerContract $c) => new ViewDataFactory($c),

            // テンプレート出力
            LatteViewGenerator::class => function (ContainerContract $c) {

                /** @var Latte */
                $latte = $c->make(Latte::class);

                $generator = new LatteViewGenerator(
                    $latte,
                );

                $generator->share('share', $c->make(ViewDataFactory::class));

                return $generator;
            },

            // リソースパス
            ResourcePath::class => function (ContainerContract $c) {

                /** @var ApplicationPath */
                $appPath = $c->make(ApplicationPath::class);

                return new ResourcePath(
                    resourcePath: $appPath->basePath(
                        config('setting.resource-path', 'resource'),
                    ),
                    lattePath: $appPath->basePath(
                        config('setting.latte-path', 'resource/latte'),
                    ),
                );
            },

            // Validator
            ValidatorInterface::class => function () {
                return Validation::createValidatorBuilder()
                    ->enableAnnotationMapping()
                    ->getValidator();
            },
        ];

        foreach ($singletons as $abstract => $class) {
            $this->app->container->singleton($abstract, $class);
        }

        $this->hook()
            // エラーハンドラーの入れ替え
            ->register(
                HttpErrorHandlerContract::class,
                fn () => new HttpErrorHandler(
                    $this->app->container->make(ApplicationEnvironment::class),
                    $this->app->container->make(Loggers::class),
                    $this->app->container,
                ),
            )
            // セッションからの入力値などの復元
            ->register(
                Session::class,
                function (Session $session) {
                    /** @var Request */
                    $request = $this->app->container->make(Request::class);

                    $inputs = new FlashOldInputs(
                        $session->getFlashBag(),
                    );

                    $inputs->put($request->request->all());

                    $this->app->container->instance(
                        FlashOldInputs::class,
                        $inputs,
                    );

                    $this->app->container->instance(
                        FlashErrorMessages::class,
                        new FlashErrorMessages(
                            $session->getFlashBag(),
                        ),
                    );

                    return $session;
                },
            )
            // コマンド登録
            ->register(
                Commands::class,
                function (Commands $commands) {
                    return $commands->add(
                        ViewClearCommand::class,
                    );
                },
                1000,
            );
    }
}
