<?php

namespace App\Tests\Repository;

use App\Entity\User;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserRepositoryTest extends KernelTestCase
{
    use FixturesTrait;

    private $entityManager;

    protected function setUp(): void
    {
        $this->loadFixtures(['App\DataFixtures\TestFixtures']);
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager()
        ;

        $kernel->shutdown();
    }

    public function testCountUsersReturnsTwo()
    {
        $number = (int) $this->entityManager->getRepository(User::class)->countUsers();

        $this->assertSame(3, $number);
    }

    public function testFindRealUsersReturnsOne()
    {
        $realUsers = $this->entityManager->getRepository(User::class)->findRealUsers();

        $this->assertSame(2, count($realUsers));
    }

    public function testGetAnonymousUser()
    {
        $anonymousUser = $this->entityManager->getRepository(User::class)->getTheAnonymousUser();
        $this->assertSame('anonymous@todoandco.com', $anonymousUser->getEmail());
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        // doing this is recommended to avoid memory leaks
        $this->entityManager->close();
        $this->entityManager = null;
    }
}
