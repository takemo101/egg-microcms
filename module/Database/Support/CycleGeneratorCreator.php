<?php

namespace Module\Database\Support;

use Takemo101\Egg\Support\Injector\ContainerContract;
use Spiral\Tokenizer\ClassesInterface;
use Cycle\Annotated\Embeddings;
use Cycle\Annotated\Entities;
use Cycle\Annotated\MergeColumns;
use Cycle\Annotated\MergeIndexes;
use Cycle\Annotated\TableInheritance;
use Cycle\Schema\Generator\GenerateModifiers;
use Cycle\Schema\Generator\GenerateRelations;
use Cycle\Schema\Generator\GenerateTypecast;
use Cycle\Schema\Generator\RenderModifiers;
use Cycle\Schema\Generator\RenderRelations;
use Cycle\Schema\Generator\RenderTables;
use Cycle\Schema\Generator\ResetTables;
use Cycle\Schema\Generator\SyncTables;
use Cycle\Schema\Generator\ValidateEntities;
use Cycle\Schema\GeneratorInterface;

final class CycleGeneratorCreator
{
    /**
     * マイグレーション生成のジェネレーターを作成
     *
     * @param ContainerContract $container
     * @return GeneratorInterface[]
     */
    public function createGenerateMigrations(ContainerContract $container): array
    {
        return [
            ...$this->createAnnotated($container),
            // Generator
            new GenerateRelations(),
            new GenerateModifiers(),
            new ValidateEntities(),
            new RenderTables(),
            new RenderRelations(),
            new RenderModifiers(),
            new GenerateTypecast(),
        ];
    }

    /**
     * スキーマ関連のジェネレーターを作成
     *
     * @param ContainerContract $container
     * @return GeneratorInterface[]
     */
    public function createSchema(ContainerContract $container): array
    {
        return [
            ...$this->createAnnotated($container),
            new ResetTables(),
            new GenerateRelations(),
            new GenerateModifiers(),
            new ValidateEntities(),
            new RenderTables(),
            new RenderRelations(),
            new RenderModifiers(),
            new SyncTables(),
            new GenerateTypecast(),
        ];
    }

    /**
     * アノテーション関連のジェネレーターを作成
     *
     * @param ContainerContract $container
     * @return GeneratorInterface[]
     */
    public function createAnnotated(ContainerContract $container): array
    {
        return [
            // Annotated
            new Embeddings($container->make(ClassesInterface::class)),
            new Entities($container->make(ClassesInterface::class)),
            new TableInheritance(),
            new MergeColumns(),
            new MergeIndexes(),
        ];
    }
}
