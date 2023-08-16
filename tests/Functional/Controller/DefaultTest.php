<?php

namespace App\Tests\Functional\Controller;

use App\Factory\UserFactory;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zenstruck\Browser\Test\HasBrowser;
use Zenstruck\Foundry\Test\ResetDatabase;

class DefaultTest extends WebTestCase
{
    use HasBrowser;
    use ResetDatabase;

    public function testGetHomepage(): void
    {
        $this->browser()
            ->get('/')
            ->assertSeeIn(
                'h1',
                "Bienvenue sur Todo List, l'application vous permettant de gérer l'ensemble de vos tâches sans effort !"
            );
    }
}
