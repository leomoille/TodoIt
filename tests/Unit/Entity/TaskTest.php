<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Task;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class TaskTest extends TestCase
{
    public function testCanGetAndSetTitle()
    {
        $task = new Task();

        $task->setTitle('Faire les courses');
        self::assertSame('Faire les courses', $task->getTitle());
    }

    public function testCanGetAndSetContent()
    {
        $task = new Task();

        $task->setContent('Acheter des fraises');
        self::assertSame('Acheter des fraises', $task->getContent());
    }

    public function testCanGetAndSetIsDone()
    {
        $task = new Task();

        $task->setIsDone(true);
        self::assertSame(true, $task->isDone());
    }

    public function testCanGetAndSetOwner()
    {
        $task = new Task();
        $user = new User();

        $task->setOwner($user);
        self::assertSame($user, $task->getOwner());
    }

    public function testCanGetAndSetCreatedAt()
    {
        $task = new Task();
        $datetime = new \DateTimeImmutable('10-10-2023');

        $task->setCreatedAt($datetime);
        self::assertSame($datetime, $task->getCreatedAt());
    }
}
