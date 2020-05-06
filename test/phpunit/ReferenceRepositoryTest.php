<?php


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
}
