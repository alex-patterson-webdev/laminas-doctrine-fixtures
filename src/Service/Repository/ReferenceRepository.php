<?php

declare(strict_types=1);

namespace Arp\LaminasDoctrineFixtures\Service\Repository;

/**
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package Arp\LaminasDoctrineFixtures\Service\Repository
 */
class ReferenceRepository extends \Doctrine\Common\DataFixtures\ReferenceRepository
{
    /**
     * @var iterable|array
     */
    private $collectionReferences = [];

    /**
     * @param string $name
     *
     * @return bool
     */
    public function hasCollectionReference(string $name): bool
    {
        return array_key_exists($name, $this->collectionReferences);
    }

    /**
     * @param string $name
     *
     * @return iterable
     */
    public function getCollectionReference(string $name): iterable
    {
        if (! $this->hasCollectionReference($name)) {
            throw new \OutOfBoundsException(sprintf('Collection reference to "%s" does not exist', $name));
        }
        return $this->collectionReferences[$name];
    }


    /**
     * @param string   $name
     * @param iterable $collection
     */
    public function setCollectionReference(string $name, iterable $collection): void
    {
        $this->collectionReferences[$name] = $collection;
    }

    /**
     * @param string   $name
     * @param iterable $collection
     *
     * @throws \BadFunctionCallException
     */
    public function addCollectionReference(string $name, iterable $collection): void
    {
        if ($this->hasCollectionReference($name)) {
            throw new \BadMethodCallException(
                sprintf(
                    'Reference to "%s" already exists, use method setCollectionReference in order to override it',
                    $name
                )
            );
        }
        $this->setCollectionReference($name, $collection);
    }
}
