<?php

declare(strict_types=1);

namespace Arp\LaminasDoctrineFixtures\Factory\Command;

use Arp\LaminasDoctrineFixtures\Command\ImportCommand;
use Arp\LaminasDoctrineFixtures\Service\Executor;
use Arp\LaminasDoctrineFixtures\Service\Loader;
use Arp\LaminasFactory\AbstractFactory;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Exception\ServiceNotCreatedException;

/**
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package Arp\LaminasDoctrineFixtures\Factory\Command
 */
final class ImportCommandFactory extends AbstractFactory
{
    /**
     * @var string
     */
    private string $defaultClassName = ImportCommand::class;

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array<mixed>|null  $options
     *
     * @return ImportCommand
     *
     * @throws ServiceNotCreatedException
     */
    public function __invoke(ContainerInterface $container, string $requestedName, array $options = null): ImportCommand
    {
        $options = $options ?? $this->getServiceOptions($container, $requestedName);

        $className = $options['class_name'] ?? $this->defaultClassName;
        $loader = $options['loader'] ?? Loader::class;
        $executor = $options['executor'] ?? Executor::class;
        $purger = $options['purger'] ?? ORMPurger::class;

        $loader = $this->getService($container, $loader, $requestedName);

        return new $className(
            $loader->getFixtures(),
            $this->getService($container, $executor, $requestedName),
            $this->getService($container, $purger, $requestedName)
        );
    }
}
