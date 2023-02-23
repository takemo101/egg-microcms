<?php

namespace Module\Database\Command;

use Cycle\Migrations\Migrator;
use Symfony\Component\Console\Output\OutputInterface;
use Takemo101\Egg\Console\Command\EggCommand;

final class InitCommand extends EggCommand
{
    public const Name = 'cycle:migrate:init';

    public const Description = 'create migrations table';

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
     * @return integer
     */
    public function handle(OutputInterface $output, Migrator $migrator): int
    {
        $migrator->configure();
        $output->writeln('<info>successfully created the migration table!</info>');

        return self::SUCCESS;
    }
}
