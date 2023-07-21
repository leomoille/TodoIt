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

    public function testGetLoginPageFromHomepage(): void
    {
        $this->browser()
            ->visit('/')
            ->click('Se connecter')
            ->assertSuccessful();
    }

    public function testLoginAsUserFromLoginPage(): void
    {
        UserFactory::createOne([
            'email' => 'test@todo.fr',
            'password' => 'password',
        ]);

        $this->browser()
            ->visit('/login')
            ->assertSeeElement('#inputEmail')
            ->fillField('inputEmail', 'test@todo.fr')
            ->assertSeeElement('#inputPassword')
            ->fillField('inputPassword', 'password')
            ->click('button')
            ->followRedirects()
            ->assertAuthenticated();
    }

    public function testLoginWithInvalidCredentialsShouldFail(): void
    {
        $this->browser()
            ->visit('/login')
            ->assertSeeElement('#inputEmail')
            ->fillField('inputEmail', 'wrong@todo.fr')
            ->assertSeeElement('#inputPassword')
            ->fillField('inputPassword', 'wrongpass')
            ->click('button')
            ->followRedirects()
            ->assertNotAuthenticated();
    }
}
