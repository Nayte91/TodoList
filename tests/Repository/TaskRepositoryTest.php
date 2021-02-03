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

    public function testFindAllTasks()
    {
        $tasks = $this->entityManager->getRepository(Task::class)->findAll();

        $this->assertSame(4, count($tasks));
    }

    public function testFindDoneTasks()
    {
        $tasks = $this->entityManager->getRepository(Task::class)->fetchTasksDone();

        $this->assertSame(1, count($tasks));
    }

    public function testFindUndoneTasks()
    {
        $tasks = $this->entityManager->getRepository(Task::class)->fetchTasksUndone();

        $this->assertSame(3, count($tasks));
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->entityManager->close();
        $this->entityManager = null;
    }
}
