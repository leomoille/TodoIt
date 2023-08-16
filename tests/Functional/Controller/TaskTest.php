<?php

namespace App\Tests\Functional\Controller;

use App\Factory\TaskFactory;
use App\Factory\UserFactory;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zenstruck\Browser\Test\HasBrowser;
use Zenstruck\Foundry\Test\ResetDatabase;

class TaskTest extends WebTestCase
{
    use HasBrowser;
    use ResetDatabase;

    public function testAnonymousCanNotAccessToTasks(): void
    {
        $this->browser()
            ->interceptRedirects()
            ->visit('/tasks')
            ->assertRedirectedTo('/login');
    }

    public function testAuthenticatedUserCanAccessToHisTasks(): void
    {
        $this->browser()
            ->actingAs(UserFactory::createOne())
            ->visit('/tasks')
            ->assertSuccessful();
    }

    public function testAuthenticatedUserCanAddTask()
    {
        $this->browser()
            ->actingAs(UserFactory::createOne())
            ->visit('/tasks')
            ->assertSuccessful()
            ->click('Créer une nouvelle tâche')
            ->assertSuccessful()
            ->assertSeeElement('#task_title')
            ->fillField('task_title', 'Faire les courses')
            ->assertSeeElement('#task_content')
            ->fillField('task_content', 'Acheter des fraises et des pommes')
            ->click('button')
            ->followRedirects()
            ->assertSuccessful();
    }

    public function testAuthenticatedUserCanToggleTaskToDone(): void
    {
        $user = UserFactory::new()->create();
        TaskFactory::new()->create(['owner' => $user, 'isDone' => false]);
        flush();

        $this->browser()
            ->actingAs($user)
            ->visit('/tasks')
            ->assertSuccessful()
            ->assertElementCount('div.card', 1)
            ->click('Marquer comme faite')
            ->assertSuccessful()
            ->assertElementCount('div.card', 0);
    }

    public function testAuthenticatedUserCanToggleTaskToUndone()
    {
        $user = UserFactory::new()->create();
        TaskFactory::new()->create(['owner' => $user, 'isDone' => true]);
        flush();

        $this->browser()
            ->actingAs($user)
            ->visit('/tasks')
            ->assertElementCount('div.card', 0)
            ->click('Consulter la liste des tâches terminées')
            ->assertElementCount('div.card', 1)
            ->click('Marquer non terminée')
            ->assertElementCount('div.card', 1)
            ->click('Consulter la liste des tâches terminées')
            ->assertElementCount('div.card', 0);
    }

    public function testUserCanEditTask()
    {
        $user = UserFactory::new()->create();
        $task = TaskFactory::new()->create(['owner' => $user, 'isDone' => false]);
        flush();

        $this->browser()
            ->actingAs($user)
            ->visit('/tasks')
            ->assertSuccessful()
            ->assertElementCount('div.card', 1)
            ->click($task->getTitle())
            ->assertSuccessful()
            ->assertFieldEquals('task_title', $task->getTitle())
            ->assertFieldEquals('task_content', $task->getContent())
            ->fillField('task_title', 'Titre de la tâche')
            ->fillField('task_content', 'Contenu de la tâche')
            ->click('button')
            ->assertSuccessful()
            ->assertElementCount('div.card', 1)
            ->assertSeeIn('.card-title', 'Titre de la tâche')
            ->assertSeeIn('.card-text', 'Contenu de la tâche');
    }

    public function testUserCanDeleteTask()
    {
        $user = UserFactory::new()->create();
        TaskFactory::new()->create(['owner' => $user, 'isDone' => false]);
        flush();

        $this->browser()
            ->actingAs($user)
            ->visit('/tasks')
            ->assertSuccessful()
            ->assertElementCount('div.card', 1)
            ->click('Supprimer')
            ->assertSuccessful()
            ->assertElementCount('div.card', 0)
            ->click('Consulter la liste des tâches terminées')
            ->assertSuccessful()
            ->assertElementCount('div.card', 0);
    }

    public function testAdminCanDeleteOrphanedTask()
    {
        $user = UserFactory::createOne([
            'roles' => ['ROLE_ADMIN'],
        ]);
        TaskFactory::new()->create(['owner' => null, 'isDone' => false]);
        flush();

        $this->browser()
            ->actingAs($user)
            ->visit('/tasks')
            ->assertSuccessful()
            ->assertElementCount('div.card', 1)
            ->click('Supprimer')
            ->assertSuccessful()
            ->assertElementCount('div.card', 0)
            ->click('Consulter la liste des tâches terminées')
            ->assertSuccessful()
            ->assertElementCount('div.card', 0);
    }

    public function testUserCanNotDeleteOtherTask(): void
    {
        $otherUser = UserFactory::new()->create();
        $otherTask = TaskFactory::new()->create(['owner' => $otherUser, 'isDone' => false]);
        $user = UserFactory::new()->create();
        flush();

        $this->browser()
            ->actingAs($user)
            ->visit('/tasks/'.$otherTask->getId().'/delete')
            ->assertStatus('404');
    }

    public function testUserCanNotToggleOtherTask(): void
    {
        $otherUser = UserFactory::new()->create();
        $otherTask = TaskFactory::new()->create(['owner' => $otherUser, 'isDone' => false]);
        $user = UserFactory::new()->create();
        flush();

        $this->browser()
            ->actingAs($user)
            ->visit('/tasks/'.$otherTask->getId().'/toggle')
            ->assertStatus('404');
    }
}
