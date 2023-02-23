<?php

namespace Module\Database\Command;

use Cycle\Migrations\Migrator;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Takemo101\Egg\Console\Command\EggCommand;

final class RollbackCommand extends EggCommand
{
    public const Name = 'cycle:migrate:rollback';

    public const Description = 'rollback the migration';

    protected function configure(): void
    {
        $this
            ->setName(self::Name)
            ->setDescription(self::Description)
            ->addOption(
                'force',
                's',
                InputOption::VALUE_NONE,
                'force rollback',
            )
            ->addOption(
                'all',
                'a',
                InputOption::VALUE_NONE,
                'perform all rollbacks',
            );
    }

    /**
     * コマンド実行
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @param Migrator $migrator
     * @return integer
     */
    public function handle(
        InputInterface $input,
        OutputInterface $output,
        Migrator $migrator,
    ): int {
        $migrator->configure();

        $found = false;
        $count = !$input->getOption('all') ? 1 : PHP_INT_MAX;

        while ($count > 0 && ($migration = $migrator->rollback())) {
            $found = true;
            --$count;

            /** @psalm-suppress InternalMethod */
            $message = sprintf(
                '<info>Migration <comment>%s</comment> was successful!</info>',
                $migration->getState()->getName()
            );

            $output->writeln($message);
        }

        if (!$found) {
            $output->writeln('<fg=red>no migrations can be rolled back</fg=red>');
        }

        return self::SUCCESS;
    }
}
