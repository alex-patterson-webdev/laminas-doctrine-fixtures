<?php

declare(strict_types=1);

namespace Arp\LaminasDoctrineFixtures\Factory\Service;

use Arp\LaminasFactory\AbstractFactory;
use Arp\LaminasFactory\Exception\ServiceNotCreatedException;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\Loader;
use Interop\Container\ContainerInterface;

/**
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package Arp\LaminasDoctrineFixtures\Factory\Service
 */
final class LoaderFactory extends AbstractFactory
{
    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return Loader
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $options = $options ?? $this->getServiceOptions($container, $requestedName);

        $loader = new Loader();

        if (! empty($options['directories'])) {
            foreach ($options['directories'] as $directory) {
                $loader->loadFromDirectory($directory);
            }
        }

        if (! empty($options['fixtures'])) {
            foreach ($this->getFixtures($container, $options['fixtures'], $requestedName) as $fixture) {
                $loader->addFixture($fixture);
            }
        }

        return $loader;
    }

    /**
     * Create the provided fixtures.
     *
     * @param ContainerInterface $container
     * @param array              $fixtures
     * @param string             $requestedName
     *
     * @return FixtureInterface[]
     *
     * @throws ServiceNotCreatedException If a fixture cannot be created
     */
    private function getFixtures(ContainerInterface $container, array $fixtures, string $requestedName): array
    {
        $fixtureObjects = [];

        foreach ($fixtures as $fixture) {
            if (is_string($fixture)) {
                if (!$container->has($fixture)) {
                    throw new ServiceNotCreatedException(
                        sprintf('The data fixture \'%s\' could not be found registered', $fixture)
                    );
                }
                $fixture = $container->get($fixture);
            }
            if (! $fixture instanceof FixtureInterface) {
                throw new ServiceNotCreatedException(
                    sprintf(
                        'The data fixture must be an object of type \'%s\'; \'%s\' provided in \'%s\'',
                        FixtureInterface::class,
                        (is_object($fixture) ? get_class($fixture) : gettype($fixture)),
                        $requestedName
                    )
                );
            }
            $fixtureObjects[] = $fixture;
        }

        return $fixtureObjects;
    }
}
