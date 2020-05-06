<?php

declare(strict_types=1);

namespace Arp\LaminasDoctrineFixtures\Service\Repository;

use Doctrine\Common\Persistence\ObjectManager;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package Arp\LaminasDoctrineFixtures\Service\Repository
 */
final class ReferenceRepositoryTest extends TestCase
{
    /**
     * @var ObjectManager|MockObject
     */
    private $objectManager;

    public function setUp(): void
    {
        $this->objectManager = $this->getMockForAbstractClass(ObjectManager::class);
    }

    /**
     * Assert that the ReferenceRepository is of type \Doctrine\Common\DataFixtures\ReferenceRepository.
     *
     * @covers \Arp\LaminasDoctrineFixtures\Service\Repository\ReferenceRepository
     */
    public function testExtendsReferenceRepository(): void
    {
        $referenceRepository = new ReferenceRepository($this->objectManager);

        $this->assertInstanceOf(\Doctrine\Common\DataFixtures\ReferenceRepository::class, $referenceRepository);
    }

    /**
     * Assert a collection reference can be set and get on a ReferenceRepository via
     * setCollectionReference() and getCollectionReference().
     *
     * @covers \Arp\LaminasDoctrineFixtures\Service\Repository\ReferenceRepository::setCollectionReference
     * @covers \Arp\LaminasDoctrineFixtures\Service\Repository\ReferenceRepository::getCollectionReference
     */
    public function testSetAndGetCollectionReference(): void
    {
        $collectionReference = new ReferenceRepository($this->objectManager);

        /** @var \stdClass[] $data */
        $data = [
            new \stdClass(),
            new \stdClass(),
            new \stdClass(),
        ];

        $collectionReference->setCollectionReference('foo', $data);

        $this->assertSame($data, $collectionReference->getCollectionReference('foo'));
    }
}
