<?php

namespace App\Tests\Entity;

use App\Entity\Task;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testGettersAndSetters()
    {
        $user = new User;

        $user
            ->setUsername('nAYtE')
            ->setEmail('NAYTE91@GMAIL.COM');
        $this->assertSame('Nayte', $user->getUsername());
        $this->assertSame('nayte91@gmail.com', $user->getEmail());
    }

    public function testSetAdminGivesRole()
    {
        $user = new User;

        $user->setAdmin(false);
        $this->assertFalse(false, $user->isAdmin());
        $this->assertCount(1, $user->getRoles());
        $this->assertContains('ROLE_USER', $user->getRoles());

        $user->setAdmin(true);
        $this->assertTrue($user->isAdmin());
        $this->assertContains('ROLE_ADMIN', $user->getRoles());
        $this->assertCount(2, $user->getRoles());
    }

    public function testAddAndRemoveTasks()
    {
        $user = new User;
        $task = new Task;

        $user->addTask($task);
        $this->assertCount(1, $user->getTasks());

        $user->removeTask($task);
        $this->assertCount(0, $user->getTasks());
    }
}
