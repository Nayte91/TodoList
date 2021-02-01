<?php

namespace App\Tests\Repository;

use App\Entity\Task;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TaskRepositoryTest extends KernelTestCase
{
    use FixturesTrait;

    private $entityManager;

    protected function setUp(): void
    {
        $this->loadFixtures(['App\Tests\Fixtures\TestFixtures']);
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $kernel->shutdown();
    }

    public function testFindTasks()
    {
        $tasks = $this->entityManager->getRepository(Task::class)->findAll();

        $this->assertSame(3, count($tasks));
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        // doing this is recommended to avoid memory leaks
        $this->entityManager->close();
        $this->entityManager = null;
    }
}
