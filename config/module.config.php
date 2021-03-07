<?php

namespace Arp\LaminasDoctrineFixtures;

use Arp\LaminasDoctrineFixtures\Command\ImportCommand;
use Arp\LaminasDoctrineFixtures\Factory\Command\ImportCommandFactory;
use Arp\LaminasDoctrineFixtures\Factory\Service\ExecutorFactory;
use Arp\LaminasDoctrineFixtures\Factory\Service\LoaderFactory;
use Arp\LaminasDoctrineFixtures\Factory\Service\PurgerFactory;
use Arp\LaminasDoctrineFixtures\Service\Executor;
use Arp\LaminasDoctrineFixtures\Service\Loader;
use Arp\LaminasDoctrineFixtures\Service\Purger;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;

return [
    'arp' => [
        'services' => [
            Loader::class => [
                'fixtures' => [],
                'directories' => [],
            ],

            Executor::class => [
                'entity_manager' => 'doctrine.entitymanager.orm_default',
                'purger' => Purger::class,
            ],

            Purger::class => [
                'entity_manager' => 'doctrine.entitymanager.orm_default',
                'mode' => ORMPurger::PURGE_MODE_DELETE,
                'excluded_table_names' => [],
            ],

            // Cli Commands
            ImportCommand::class => [
                'loader' => Loader::class,
                'executor' => Executor::class,
                'purger' => Purger::class,
            ],
        ],
    ],
    'service_manager' => [
        'factories' => [
            // Cli Command
            ImportCommand::class => ImportCommandFactory::class,

            // DataFixtures components
            Loader::class => LoaderFactory::class,
            Executor::class => ExecutorFactory::class,
            Purger::class => PurgerFactory::class,
        ],
    ],
];
