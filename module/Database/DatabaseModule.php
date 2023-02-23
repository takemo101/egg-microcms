<?php

namespace Module\Database;

use Takemo101\Egg\Module\Module;
use Cycle\Database\DatabaseManager;
use Cycle\Database\Config\DatabaseConfig;
use Cycle\Database\DatabaseInterface;
use Cycle\Database\DatabaseProviderInterface;
use Spiral\Tokenizer\ClassesInterface;
use Spiral\Tokenizer\ClassLocator;
use Spiral\Tokenizer\Config\TokenizerConfig;
use Spiral\Tokenizer\Tokenizer;
use Cycle\ORM\ORM;
use Cycle\ORM\Factory;
use Cycle\ORM\ORMInterface;
use Cycle\ORM\Schema;
use Cycle\ORM\EntityManager;
use Cycle\ORM\EntityManagerInterface;
use Cycle\Schema\Compiler;
use Cycle\Schema\Registry;
use Cycle\Migrations\Config\MigrationConfig;
use Cycle\Migrations\FileRepository;
use Cycle\Migrations\Migrator;
use Module\Database\Command\InitCommand;
use Module\Database\Command\MigrateCommand;
use Module\Database\Command\RollbackCommand;
use Module\Database\Command\SchemaGenerateCommand;
use Module\Database\Command\SchemaMakeCommand;
use Module\Database\Support\CycleGeneratorCreator;
use Takemo101\Egg\Console\Commands;

final class DatabaseModule extends Module
{
    /**
     * モジュールを起動する
     *
     * @return void
     */
    public function boot(): void
    {
        $this->app->container->singleton(
            DatabaseProviderInterface::class,
            fn () => new DatabaseManager(
                new DatabaseConfig(config('cycle.database', []))
            )
        )
            ->alias(
                DatabaseProviderInterface::class,
                DatabaseManager::class
            );

        $this->app->container->bind(
            DatabaseInterface::class,
            fn () => $this->app->container
                ->make(DatabaseProviderInterface::class)
                ->database(),
        );

        $this->app->container->singleton(
            Tokenizer::class,
            fn () => new Tokenizer(
                new TokenizerConfig(config('cycle.orm.tokenizer', [])),
            )
        );

        $this->app->container->singleton(
            ClassesInterface::class,
            fn () => $this->app->container->make(Tokenizer::class)
                ->classLocator(),
        )
            ->alias(
                ClassesInterface::class,
                ClassLocator::class,
            );

        $this->app->container->singleton(
            Schema::class,
            function () {

                $creator = new CycleGeneratorCreator();

                return new Schema(
                    (new Compiler())->compile(
                        $this->app->container
                            ->make(Registry::class),
                        $creator->createSchema(
                            $this->app->container,
                        ),
                    ),
                );
            },
        );

        $this->app->container->singleton(
            ORMInterface::class,
            function () {
                /** @var DatabaseProviderInterface */
                $dbal = $this->app->container->make(DatabaseProviderInterface::class);
                return new ORM(
                    new Factory($dbal),
                    $this->app->container->make(Schema::class),
                );
            },
        )
            ->alias(
                ORMInterface::class,
                ORM::class
            );

        $this->app->container->singleton(
            EntityManagerInterface::class,
            fn () => new EntityManager(
                $this->app->container->make(ORMInterface::class),
            ),
        )
            ->alias(
                EntityManagerInterface::class,
                EntityManager::class,
            );

        $this->app->container->singleton(
            Migrator::class,
            function () {
                /** @var DatabaseManager */
                $dbal = $this->app->container->make(DatabaseProviderInterface::class);

                $config = new MigrationConfig(config('cycle.migrations', []));

                $repository = new FileRepository($config);

                return new Migrator($config, $dbal, $repository);
            },
        );

        $this->hook()->register(
            Commands::class,
            fn (Commands $commands) => $commands->add(
                InitCommand::class,
                MigrateCommand::class,
                RollbackCommand::class,
                SchemaGenerateCommand::class,
            ),
            2000,
        );
    }
}
