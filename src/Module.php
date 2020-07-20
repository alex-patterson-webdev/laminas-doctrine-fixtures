<?php

declare(strict_types=1);

namespace Arp\LaminasDoctrineFixtures;

use Arp\LaminasDoctrineFixtures\Command\ImportCommand;
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Laminas\EventManager\Event;
use Laminas\ModuleManager\ModuleManager;
use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Application;

/**
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package Arp\LaminasDoctrineFixtures
 */
class Module
{
    /**
     * @param ModuleManager $moduleManager
     */
    public function init(ModuleManager $moduleManager): void
    {
        $eventManager = $moduleManager->getEventManager()->getSharedManager();

        if (null === $eventManager) {
            return;
        }

        $eventManager->attach('doctrine', 'loadCli.post', [$this, 'addImportCommand']);
    }

    /**
     * @param Event $event
     */
    public function addImportCommand(Event $event): void
    {
        /** @var Application $cliApplication */
        $cliApplication = $event->getTarget();

        $container = $event->getParam('ServiceManager', null);

        if (
            null === $container
            || ! $container instanceof ContainerInterface
            || ! $container->has(ImportCommand::class)
        ) {
            return;
        }

        ConsoleRunner::addCommands($cliApplication);

        $cliApplication->add($container->get(ImportCommand::class));
    }

    /**
     * @return array
     */
    public function getConfig(): array
    {
        return require __DIR__ . '/../config/module.config.php';
    }
}
