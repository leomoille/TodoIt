<?php

namespace App\Tests\Functional\Controller;

use App\Factory\UserFactory;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zenstruck\Browser\Test\HasBrowser;
use Zenstruck\Foundry\Test\ResetDatabase;

class UserTest extends WebTestCase
{
    use HasBrowser;
    use ResetDatabase;

    public function testAnonymousCanNotAccessToUserList(): void
    {
        $this->browser()
            ->interceptRedirects()
            ->visit('/users')
            ->assertRedirectedTo('/login');
    }

    public function testUserCanNotAccessToUserList(): void
    {
        $user = UserFactory::new()->create();

        $this->browser()
            ->actingAs($user)
            ->interceptRedirects()
            ->visit('/users')
            ->assertStatus('403');
    }

    public function testAdminCanAccessToUserList(): void
    {
        $user = UserFactory::createOne([
            'roles' => ['ROLE_ADMIN'],
        ]);

        $this->browser()
            ->actingAs($user)
            ->interceptRedirects()
            ->visit('/users')
            ->assertSuccessful();
    }

    public function testAdminCanAddUser()
    {
        $user = UserFactory::createOne([
            'roles' => ['ROLE_ADMIN'],
        ]);

        $this->browser()
            ->actingAs($user)
            ->visit('/')
            ->click('Créer un utilisateur')
            ->fillField('user_username', 'johnDoE')
            ->fillField('user_password_first', 'password')
            ->fillField('user_password_second', 'password')
            ->fillField('user_email', 'test@todo.fr')
            ->interceptRedirects()
            ->click('Ajouter')
            ->assertRedirected()
            ->followRedirects()
            ->assertSee('johnDoE');
    }

    public function testAdminCanSwitchUserToAdmin()
    {
        $admin = UserFactory::createOne([
            'roles' => ['ROLE_ADMIN'],
        ]);

        $user = UserFactory::createOne([
            'username' => 'johndoe',
            'email' => 'johndoe@todo.fr',
        ]);

        $this->browser()
            ->actingAs($admin)
            ->visit('/')
            ->click('Voir les utilisateurs')
            ->assertSuccessful()
            ->assertSee('johndoe')
            ->click('#johndoe')
            ->assertSeeIn('h1', 'Modifier johndoe')
            ->checkField('Administrateur')
            ->click('Modifier')
            ->assertSuccessful()
            ->assertSee('Superbe ! L\'utilisateur a bien été modifié');
    }
}
