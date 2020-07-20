<?php

declare(strict_types=1);

namespace Arp\LaminasDoctrineFixtures\Command;

use Arp\LaminasDoctrineFixtures\Service\Executor;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package Arp\LaminasDoctrineFixtures\Command
 */
class ImportCommand extends Command
{
    /**
     * @var FixtureInterface[]
     */
    private $fixtures;

    /**
     * @var Executor
     */
    private $executor;

    /**
     * @var ORMPurger|null
     */
    private $purger;

    /**
     * @param FixtureInterface[] $fixtures
     * @param Executor           $executor
     * @param ORMPurger|null     $purger
     */
    public function __construct(array $fixtures, Executor $executor, ORMPurger $purger = null)
    {
        $this->fixtures = $fixtures;
        $this->executor = $executor;
        $this->purger = $purger;

        parent::__construct();
    }

    /**
     * Configure the command's options.
     */
    protected function configure(): void
    {
        parent::configure();

        $this->setName('data-fixture:import')
            ->setDescription('Import Data Fixtures')
            ->setHelp('The import command Imports data-fixtures')
            ->addOption('append', null, InputOption::VALUE_NONE, 'Append data to existing data.');

        if (null !== $this->purger) {
            $this->addOption(
                'purge-with-truncate',
                null,
                InputOption::VALUE_NONE,
                'Truncate tables before inserting data'
            );
        }
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int|void|null
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Executing data fixtures...');

        $purgeMode = $input->getOption('purge-with-truncate');

        if (null !== $this->purger && $purgeMode) {
            $output->writeln(sprintf('Purging existing database data'));

            $this->purger->setPurgeMode((2 === $purgeMode) ? 2 : 1);
        }

        $this->executor->execute($this->fixtures, $input->getOption('append') ? true : false);

        $output->writeln(sprintf('Completed execution of \'%d\' fixtures', count($this->fixtures)));

        return 0;
    }
}
