<?php

declare(strict_types=1);

namespace Arp\LaminasDoctrineFixtures\Factory\Service;

use Arp\LaminasDoctrineFixtures\Service\Executor;
use Arp\LaminasDoctrineFixtures\Service\Repository\ReferenceRepository;
use Arp\LaminasFactory\AbstractFactory;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManagerInterface;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Exception\ServiceNotCreatedException;

/**
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package Arp\LaminasDoctrineFixtures\Factory\Service
 */
final class ExecutorFactory extends AbstractFactory
{
    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array<mixed>|null  $options
     *
     * @return Executor
     *
     * @throws ServiceNotCreatedException
     */
    public function __invoke(ContainerInterface $container, string $requestedName, array $options = null): Executor
    {
        $options = $options ?? $this->getServiceOptions($container, $requestedName);

        $entityManager = $options['entity_manager'] ?? EntityManagerInterface::class;
        $purger = $options['purger'] ?? ORMPurger::class;

        /** @var EntityManagerInterface|string */
        if (is_string($entityManager)) {
            $entityManager = $this->getService($container, $entityManager, $requestedName);
        }

        if (is_string($purger)) {
            $purger = $this->getService($container, $purger, $requestedName);
        }

        $executor = new Executor($entityManager, $purger);
        $executor->setReferenceRepository(new ReferenceRepository($entityManager));

        return $executor;
    }
}
