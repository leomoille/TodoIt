<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Task;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testCanGetAndSetEmail()
    {
        $user = new User();

        $user->setEmail('test@todo.fr');
        self::assertSame('test@todo.fr', $user->getEmail());
    }

    public function testCanGetAndSetUsername()
    {
        $user = new User();

        $user->setUsername('todo-guy');
        self::assertSame('todo-guy', $user->getUsername());
    }

    public function testCanGetAndSetRoles()
    {
        $user = new User();

        $user->setRoles(['ROLE_ADMIN']);
        self::assertSame(['ROLE_ADMIN', 'ROLE_USER'], $user->getRoles());
    }

    public function testCanGetAndSetPassword()
    {
        $user = new User();

        $user->setPassword('password');
        self::assertSame('password', $user->getPassword());
    }

    public function testCanGetAndSetTask()
    {
        $user = new User();
        $task = new Task();
        $task
            ->setOwner($user)
            ->setCreatedAt(new \DateTimeImmutable())
            ->setTitle('Test')
            ->setContent('Content')
            ->setIsDone(false);

        $user->addTask($task);
        self::assertSame($task, $user->getTasks()[0]);
    }

    public function testCanRemoveTask()
    {
        $user = new User();
        $task = new Task();
        $task
            ->setOwner($user)
            ->setCreatedAt(new \DateTimeImmutable())
            ->setTitle('Test')
            ->setContent('Content')
            ->setIsDone(false);

        $user->removeTask($task);
        self::assertEmpty($user->getTasks());
    }
}
