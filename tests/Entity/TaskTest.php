<?php

namespace App\Tests\Entity;

use App\Entity\Task;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class TaskTest extends TestCase
{
    public function testGettersAndSetters()
    {
        $task = new Task;
        $this->assertInstanceOf(\DateTime::class, $task->getCreatedAt());

        $task->setTitle('TEST')->setContent('Ceci est un test nul');

        $this->assertSame('TEST', $task->getTitle());
        $this->assertSame('Ceci est un test nul', $task->getContent());
    }

    public function testToggle()
    {
        $task = new Task;
        $this->assertFalse($task->isDone());

        $task->toggle(true);
        $this->assertTrue($task->isDone());
    }

    public function testAttachTaskToUser()
    {
        $task = new Task;
        $this->assertNull($task->getOwner());

        $user = new User;
        $task->setOwner($user);
        $this->assertSame($user, $task->getOwner());
    }
}
