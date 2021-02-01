<?php

namespace App\Tests\Controller;
;

use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

final class TaskControllerTest extends WebTestCase
{
    use FixturesTrait;

    protected function setUp(): void
    {
        $this->loadFixtures(['App\Tests\Fixtures\TestFixtures']);
        $this->ensureKernelShutdown();
    }

    public function testListTasksPage()
    {
        $client = $this->createClientUnlogged();
        $taskIndex = $client->request('GET', '/tasks');

        $this->assertSame(3, count($taskIndex->filter('span.glyphicon-remove')));
    }

    public function testDeleteTaskNotOwned()
    {
        $this->expectException(AccessDeniedException::class);

        $client = $this->createClientLoggedAsBasicUser();
        $client->catchExceptions(false);

        $client->request('GET', '/tasks/2/delete');
    }

    public function testDeleteTaskBeingOwned()
    {
        $client = $this->createClientLoggedAsBasicUser();

        $client->request('GET', '/tasks/3/delete');

        $this->assertSelectorTextContains('div.alert-success', 'La tâche a bien été supprimée.');
    }

    public function testDeleteAnonymousTaskNotAdmin()
    {
        $this->expectException(AccessDeniedException::class);

        $client = $this->createClientLoggedAsBasicUser();
        $client->catchExceptions(false);

        $client->request('GET', '/tasks/1/delete');
    }

    public function testDeleteAnonymousTaskBeingAdmin()
    {
        $client = $this->createClientLoggedAsAdminUser();

        $client->request('GET', '/tasks/1/delete');

        $this->assertSelectorTextContains('div.alert-success', 'La tâche a bien été supprimée.');
    }

    public function testCreateTaskAnonymously()
    {
        $client = $this->createClientUnlogged();

        $client->request('GET', '/tasks/create');
        $client->submitForm('Ajouter', [
            'task[title]' => 'toto',
            'task[content]' => 'test task'
        ]);

        $this->assertSelectorTextContains('div.alert-success', 'La tâche a bien été ajoutée.');
    }

    public function testCreateTaskLogged()
    {
        $client = $this->createClientLoggedAsBasicUser();

        $client->request('GET', '/tasks/create');
        $client->submitForm('Ajouter', [
            'task[title]' => 'toto',
            'task[content]' => 'test task'
        ]);

        $this->assertSelectorTextContains('div.alert-success', 'La tâche a bien été ajoutée.');
    }

    public function testTaskEditionWithOwner()
    {
        $client = $this->createClientUnlogged();

        $client->request('GET', '/tasks/2/edit');
        $client->submitForm('Modifier', [
            'task[title]' => 'toto',
            'task[content]' => 'test task'
        ]);

        $this->assertSelectorTextContains('div.alert-success', 'La tâche a bien été modifiée.');
    }

    public function testEditTaskPage()
    {
        $client = $this->createClientUnlogged();

        $taskEdition = $client->request('GET', '/tasks/2/edit');

        $this->assertSame(2, count($taskEdition->filter('[required="required"]')));
    }

    public function testToggleTask()
    {
        $client = $this->createClientUnlogged();

        $client->request('GET', '/tasks/2/toggle');

        $this->assertSelectorTextContains('div.alert-success', 'a bien été marquée comme faite.');
    }

    private function createClientLoggedAsBasicUser()
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'basic@changezmoi.fr',
            'PHP_AUTH_PW' => 'basic',
        ]);
        $client->followRedirects();

        return $client;
    }

    private function createClientLoggedAsAdminUser()
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'admin@changezmoi.fr',
            'PHP_AUTH_PW' => 'admin',
        ]);
        $client->followRedirects();

        return $client;
    }

    private function createClientUnlogged()
    {
        $client = static::createClient();
        $client->followRedirects();

        return $client;
    }
}
