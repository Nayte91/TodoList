<?php

namespace App\Tests\Controller;

use App\Controller\TaskController;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TaskControllerTest extends WebTestCase
{
    use FixturesTrait;

    protected function setUp(): void
    {
        $this->loadFixtures(['App\DataFixtures\AppFixtures']);
        $this->ensureKernelShutdown();
    }

    public function testListAction()
    {
        $client = static::createClient();
        $taskIndex = $client->request('GET', '/tasks');

        $this->assertSame(3, count($taskIndex->filter('span.glyphicon-remove')));
    }
}
