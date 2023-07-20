<?php

namespace App\DataFixtures;

use App\Factory\TaskFactory;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        TaskFactory::createMany(10);

        TaskFactory::createMany(10, [
            'owner' => UserFactory::createOne([
                'email' => 'user@todo.fr',
                'password' => 'password',
                'username' => 'leouser',
            ]),
        ]);

        TaskFactory::createMany(5, [
            'owner' => UserFactory::createOne([
                'email' => 'admin@todo.fr',
                'password' => 'password',
                'username' => 'leoadmin',
                'roles' => ['ROLE_ADMIN'],
            ]),
        ]);
    }
}
