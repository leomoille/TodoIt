<?php

namespace App\Tests\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TaskTest extends WebTestCase
{
    public function setUp(): void
    {
        $client = static::createClient();
        $this->userRepository =
            $client
                ->getContainer()
                ->get('doctrine.orm.entity_manager')
                ->getRepository(User::class);

        $this->user = $this->userRepository->findOneByEmail('test@todo.fr');

        $this->urlGenerator = $client->getContainer()->get('router.default');

        $client->loginUser($this->user);
    }

    public function testSomething(): void
    {
        $this->assertSame(1, 1);
    }
}
