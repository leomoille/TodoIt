<?php

namespace App\Tests\Functional\Controller;

use App\Factory\UserFactory;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zenstruck\Browser\Test\HasBrowser;
use Zenstruck\Foundry\Test\ResetDatabase;

class SecurityTest extends WebTestCase
{
    use HasBrowser;
    use ResetDatabase;

    public function testCanGetLoginPageFromHomepage(): void
    {
        $this->browser()
            ->visit('/')
            ->click('Se connecter')
            ->assertSuccessful();
    }

    public function testUserCanLoginFromLoginPage(): void
    {
        UserFactory::createOne([
            'email' => 'test@todo.fr',
            'username' => 'testUser',
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
            ->assertAuthenticated()
            ->assertSee('Bonjour testUser !');
    }

    public function testCanNotLoginWithInvalidCredentialsShouldFail(): void
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
