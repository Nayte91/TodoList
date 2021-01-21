<?php


namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TaskControllerTest extends WebTestCase
{
    public function testTaskIsLinkedToUser()
    {
        $client = static::createClient();
        $client->request('get', '/tasks/create');
        //Authentifier

        //Créer une tache par le controlleur

        //Vérifier l'appartenance de la tache
        $this->assertResponseStatusCodeSame(200, $client->getResponse()->getStatusCode());
    }
}