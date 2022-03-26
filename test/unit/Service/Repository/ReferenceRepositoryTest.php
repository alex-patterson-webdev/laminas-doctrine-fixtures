<?php

declare(strict_types=1);

namespace ArpTest\LaminasDoctrineFixtures\Service\Repository;

use Arp\LaminasDoctrineFixtures\Service\Repository\ReferenceRepository;
use Doctrine\Persistence\ObjectManager;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Arp\LaminasDoctrineFixtures\Service\Repository\ReferenceRepository
 *
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package ArpTest\LaminasDoctrineFixtures\Service\Repository
 */
final class ReferenceRepositoryTest extends TestCase
{
    /**
     * @var ObjectManager&MockObject
     */
    private $objectManager;

    public function setUp(): void
    {
        $this->objectManager = $this->getMockForAbstractClass(ObjectManager::class);
    }

    /**
     * Assert that the ReferenceRepository is of type ReferenceRepository
     */
    public function testExtendsReferenceRepository(): void
    {
        $referenceRepository = new ReferenceRepository($this->objectManager);

        $this->assertInstanceOf(ReferenceRepository::class, $referenceRepository);
    }

    /**
     * Assert a collection reference can be set and get on a ReferenceRepository via
     * setCollectionReference() and getCollectionReference()
     *
     * @throws \OutOfBoundsException
     */
    public function testSetAndGetCollectionReference(): void
    {
        /** @var ReferenceRepository&MockObject $collectionReference */
        $collectionReference = $this->getMockBuilder(ReferenceRepository::class)
            ->setConstructorArgs([$this->objectManager])
            ->onlyMethods(['setReference', 'getReference'])
            ->getMock();

        $collectionName = 'FooCollection';

        $data = [
            'A' => new \stdClass(),
            'B' => new \stdClass(),
            'C' => new \stdClass(),
        ];

        $setReferenceArgs = $getReferenceArgs = [];
        foreach ($data as $index => $item) {
            $itemName = $collectionName . '.' . $index;
            $setReferenceArgs[] = [$itemName, $item];
            $getReferenceArgs[] = [$itemName];
        }

        $collectionReference->expects($this->exactly(count($setReferenceArgs)))
            ->method('setReference')
            ->withConsecutive(...$setReferenceArgs);

        $collectionReference->expects($this->exactly(count($setReferenceArgs)))
            ->method('getReference')
            ->withConsecutive(...$getReferenceArgs)
            ->willReturn(...array_values($data));

        $collectionReference->setCollectionReference($collectionName, $data);

        $this->assertSame($data, $collectionReference->getCollectionReference($collectionName));
    }
}
