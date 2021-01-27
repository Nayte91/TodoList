<?php

namespace App\Tests\Controller;

use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class UserControllerTest extends WebTestCase
{
    use FixturesTrait;

    protected function setUp(): void
    {
        $this->loadFixtures(['App\Tests\Fixtures\TestFixtures']);
        $this->ensureKernelShutdown();
    }

    public function testUserPagesAreForbiddenWithoutLogin()
    {
        $this->expectException(AccessDeniedException::class);
        $client = $this->createClientUnlogged();
        $client->catchExceptions(false);

        $client->request('GET', '/users');
    }

    public function testUserPagesAreForbiddenWithoutAdmin()
    {
        $this->expectException(AccessDeniedException::class);

        $client = $this->createClientLoggedAsBasicUser();
        $client->catchExceptions(false);

        $client->request('GET', '/users/create');
    }

    public function testUserPagesAreReachableByAdmin()
    {
        $client = $this->createClientLoggedAsAdminUser();

        $client->request('GET', '/users/2/edit');

        $this->assertResponseIsSuccessful();
    }

    public function testUserCreation()
    {
        $client = $this->createClientLoggedAsAdminUser();

        $client->request('POST', '/users/create');
        $client->submitForm('Ajouter',  [
            'user[username]' => 'toto',
            'user[password][first]' => 'toto',
            'user[password][second]' => 'toto',
            'user[email]' => 'toto@gmail.com',
            'user[admin]' => '1'
        ]);

        $this->assertSelectorTextContains('div.alert-success','L\'utilisateur a bien été ajouté.');
    }

    public function testUserEdition()
    {
        $client = $this->createClientLoggedAsAdminUser();

        $client->request('POST', '/users/3/edit');

        $client->submitForm('Modifier',  [
            'user[username]' => 'toto',
            'user[password][first]' => 'toto',
            'user[password][second]' => 'toto',
            'user[email]' => 'toto@gmail.com',
        ]);

        $this->assertSelectorTextContains('div.alert-success','L\'utilisateur a bien été modifié.');
    }

    private function createClientUnlogged(bool $withRedirects = true)
    {
        $client = static::createClient();
        if ($withRedirects) $client->followRedirects();

        return $client;
    }

    private function createClientLoggedAsBasicUser(bool $withRedirects = true)
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'basic@changezmoi.fr',
            'PHP_AUTH_PW'   => 'basic',
        ]);

        if ($withRedirects) $client->followRedirects();

        return $client;
    }

    private function createClientLoggedAsAdminUser(bool $withRedirects = true)
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'admin@changezmoi.fr',
            'PHP_AUTH_PW'   => 'admin',
        ]);

        if ($withRedirects) $client->followRedirects();

        return $client;
    }
}
