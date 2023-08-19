<?php

namespace App\Tests\Functional\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zenstruck\Browser\Test\HasBrowser;
use Zenstruck\Foundry\Test\ResetDatabase;

class RegistrationTest extends WebTestCase
{
    use HasBrowser;
    use ResetDatabase;

    public function testCanGetRegistrationPageFromHomepage(): void
    {
        $this->browser()
            ->visit('/')
            ->click('S\'inscrire')
            ->assertSuccessful();
    }

    public function testUserCanRegisterFromRegisterPage(): void
    {
        $this->browser()
            ->visit('/register')
            ->assertSeeElement('#registration_form_email')
            ->fillField('registration_form_email', 'registration@todo.fr')
            ->assertSeeElement('#registration_form_username')
            ->fillField('registration_form_username', 'TestRegistration')
            ->assertSeeElement('#registration_form_plainPassword')
            ->fillField('registration_form_plainPassword', 'password')
            ->click('button')
            ->followRedirects()
            ->assertAuthenticated()
            ->assertSee('Bonjour TestRegistration !');
    }
}
