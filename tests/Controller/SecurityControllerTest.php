<?php

namespace App\Tests\Controller;


use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityControllerTest extends WebTestCase
{
    use FixturesTrait;

    protected function setUp(): void
    {
        $this->loadFixtures(['App\DataFixtures\AppFixtures']);
        $this->ensureKernelShutdown();
    }

    public function testLoginSession()
    {
        $client = static::createClient();
        $loginpage = $client->request('GET', '/login');

        $this->assertEquals(2,count($loginpage->filter('input.form-control')));
    }
}
