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
        UserFactory::createOne([
            'email' => 'todo@list.fr',
            'password' => 'password',
            'username' => 'leotodo',
        ]);
    }
}
