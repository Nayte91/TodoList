<?php

namespace App\Tests\Controller;
;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TaskControllerTest extends WebTestCase
{
    use FixturesTrait;

    protected function setUp(): void
    {
        $this->loadFixtures(['App\DataFixtures\TestFixtures']);
        $this->ensureKernelShutdown();
    }

    public function testListTasksPage()
    {
        $client = $this->createClientUnlogged();
        $taskIndex = $client->request('GET', '/tasks');

        $this->assertSame(3, count($taskIndex->filter('span.glyphicon-remove')));
    }

    public function testDeleteTaskNotOwnedSendsError()
    {
        $client = $this->createClientLoggedAsBasicUser();

        $client->request('GET', '/tasks/2/delete');

        $this->assertSelectorTextContains('div.alert-danger','Vous ne pouvez supprimer une tâche qui ne vous appartient pas.');
    }

    public function testDeleteAdminTaskWithoutRights()
    {
        $client = $this->createClientLoggedAsBasicUser();

        $client->request('GET', '/tasks/1/delete');

        $this->assertSelectorTextContains('div.alert-danger','Cette tâche ne peut être supprimée que par un administrateur.');
    }

    public function testDeleteTaskCorrectly()
    {
        $client = $this->createClientLoggedAsAdminUser();

        $client->request('GET', '/tasks/2/delete');

        $this->assertSelectorTextContains('div.alert-success','La tâche a bien été supprimée.');
    }

    public function testCreateTaskAnonymously()
    {
        $client = $this->createClientUnlogged();

        $client->request('GET', '/tasks/create');
        $client->submitForm('Ajouter',  [
            'task[title]' => 'toto',
            'task[content]' => 'test task'
        ]);

        $this->assertSelectorTextContains('div.alert-success','La tâche a bien été ajoutée.');
    }

    public function testTaskEdition()
    {
        $client = $this->createClientUnlogged();

        $client->request('GET', '/tasks/2/edit');
        $client->submitForm('Modifier',  [
            'task[title]' => 'toto',
            'task[content]' => 'test task'
        ]);

        $this->assertSelectorTextContains('div.alert-success','La tâche a bien été modifiée.');
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

        $this->assertSelectorTextContains('div.alert-success','a bien été marquée comme faite.');
    }

    private function createClientLoggedAsBasicUser()
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'juste.leblanc@todoandco.com',
            'PHP_AUTH_PW'   => 'juste',
        ]);
        $client->followRedirects();

        return $client;
    }

    private function createClientLoggedAsAdminUser()
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'admin@changezmoi.fr',
            'PHP_AUTH_PW'   => 'admin',
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
