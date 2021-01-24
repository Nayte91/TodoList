<?php


namespace App\Tests\Controller;

use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    use FixturesTrait;

    protected function setUp(): void
    {
        $this->loadFixtures(['App\DataFixtures\AppFixtures']);
        $this->ensureKernelShutdown();
    }

    public function testShowPost()
    {
        $client = static::createClient();
        $client->request('GET', '/');

        $this->assertResponseStatusCodeSame(200, $client->getResponse()->getStatusCode());
    }
}