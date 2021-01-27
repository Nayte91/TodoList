<?php

namespace App\Tests\Controller;


use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityControllerTest extends WebTestCase
{
    use FixturesTrait;

    protected function setUp(): void
    {
        $this->loadFixtures(['App\Tests\Fixtures\TestFixtures']);
        $this->ensureKernelShutdown();
    }

    public function testLoginSession()
    {
        $client = static::createClient();
        $client->followRedirects();

        $client->request('GET', '/login');
        $client->submitForm('Sign in',  [
            'email' => 'admin@changezmoi.fr',
            'password' => 'admin'
        ]);

        $this->assertSelectorTextContains('a.btn-danger','Se déconnecter');
    }
}
