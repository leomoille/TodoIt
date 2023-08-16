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

    public function testGetLoginPageFromHomepage(): void
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
