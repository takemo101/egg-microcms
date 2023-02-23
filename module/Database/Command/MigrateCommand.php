<?php

namespace Module\Database\Command;

use Cycle\Migrations\Migrator;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Takemo101\Egg\Console\Command\EggCommand;

final class MigrateCommand extends EggCommand
{
    public const Name = 'cycle:migrate';

    public const Description = 'run migration';

    protected function configure(): void
    {
        $this
            ->setName(self::Name)
            ->setDescription(self::Description)
            ->addOption(
                'force',
                's',
                InputOption::VALUE_NONE,
                'force migration',
            )
            ->addOption(
                'one',
                'o',
                InputOption::VALUE_NONE,
                'run only one migration',
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
        $count = $input->getOption('one') ? 1 : PHP_INT_MAX;

        while ($count > 0 && ($migration = $migrator->run())) {
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
            $output->writeln('<fg=red>no migrations to run</fg=red>');
        }

        return self::SUCCESS;
    }
}
