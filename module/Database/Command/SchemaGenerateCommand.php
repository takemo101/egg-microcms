<?php

namespace Module\Database\Command;

use Cycle\Migrations\Migrator;
use Cycle\Schema\Compiler;
use Cycle\Schema\Generator\Migrations\GenerateMigrations;
use Cycle\Schema\Registry;
use Module\Database\Support\CycleGeneratorCreator;
use Symfony\Component\Console\Output\OutputInterface;
use Takemo101\Egg\Console\Command\EggCommand;

final class SchemaGenerateCommand extends EggCommand
{
    public const Name = 'cycle:schema:generate';

    public const Description = 'generate cycle schema from database and annotated classes';

    protected function configure(): void
    {
        $this
            ->setName(self::Name)
            ->setDescription(self::Description);
    }

    /**
     * コマンド実行
     *
     * @param OutputInterface $output
     * @param Migrator $migrator
     * @return integer
     */
    public function handle(
        OutputInterface $output,
        Registry $registry,
        Migrator $migrator,
    ): int {

        (new Compiler())->compile(
            $registry,
            (new CycleGeneratorCreator())->createGenerateMigrations($this->container),
        );

        $generator = new GenerateMigrations(
            $migrator->getRepository(),
            $migrator->getConfig(),
        );

        $generator->run($registry);

        $output->writeln('<info>done!</info>');

        return self::SUCCESS;
    }
}
