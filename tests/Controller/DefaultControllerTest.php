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

    public function testHomepageHasButtons()
    {
        $client = static::createClient();
        $homepage = $client->request('GET', '/');

        $this->assertEquals(4,count($homepage->filter('a.btn')));
        $this->assertSame('Se connecter', $homepage->filter('a.btn')->eq(0)->text());
    }
}