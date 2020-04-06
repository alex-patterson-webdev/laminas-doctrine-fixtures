<?php

declare(strict_types=1);

namespace Arp\LaminasDoctrineFixtures\Factory\Service;

use Arp\LaminasFactory\AbstractFactory;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManagerInterface;
use Interop\Container\ContainerInterface;

/**
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package Arp\LaminasDoctrineFixtures\Factory\Service
 */
final class PurgerFactory extends AbstractFactory
{
    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return ORMPurger
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): ORMPurger
    {
        $options = $options ?? $this->getServiceOptions($container, $requestedName);

        $entityManager = $options['entity_manager'] ?? EntityManagerInterface::class;
        $mode = $options['mode'] ?? ORMPurger::PURGE_MODE_DELETE;
        $excludedTableNames = $options['excluded_table_names'] ?? [];

        $purger = new ORMPurger(
            $this->getService($container, $entityManager, $requestedName),
            $excludedTableNames
        );
        $purger->setPurgeMode($mode);

        return $purger;
    }
}
