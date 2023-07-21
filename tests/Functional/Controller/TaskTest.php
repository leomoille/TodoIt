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

    public function testAnonymousShouldNotAccessToTasks(): void
    {
        $this->browser()
            ->interceptRedirects()
            ->visit('/tasks')
            ->assertRedirectedTo('/login');
    }

    public function testAuthenticatedUserShouldAccessToHisTasks(): void
    {
        $this->browser()
            ->actingAs(UserFactory::createOne())
            ->visit('/tasks')
            ->assertSuccessful();
    }

    public function testAuthenticatedUserShouldAddTask()
    {
        $this->browser()
            ->actingAs(UserFactory::createOne())
            ->visit('/tasks')
            ->click('Créer une nouvelle tâche')
            ->assertSeeElement('#task_title')
            ->fillField('task_title', 'Faire les courses')
            ->assertSeeElement('#task_content')
            ->fillField('task_content', 'Acheter des fraises et des pommes')
            ->click('button')
            ->followRedirects()
            ->assertSuccessful();
    }

    public function testAuthenticatedUserShouldToggleTaskToDone(): void
    {
        $user = UserFactory::new()->create();
        TaskFactory::new()->create(['owner' => $user, 'isDone' => false]);
        flush();

        $this->browser()
            ->actingAs($user)
            ->visit('/tasks')
            ->assertElementCount('div.card', 1)
            ->click('Marquer comme faite')
            ->assertElementCount('div.card', 0);
    }

    public function testAuthenticatedUserShouldToggleTaskToUndone()
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

    public function testUserShouldEditTask()
    {
        $user = UserFactory::new()->create();
        $task = TaskFactory::new()->create(['owner' => $user, 'isDone' => false]);
        flush();

        $this->browser()
            ->actingAs($user)
            ->visit('/tasks')
            ->assertElementCount('div.card', 1)
            ->click($task->getTitle())
            ->assertFieldEquals('task_title', $task->getTitle())
            ->assertFieldEquals('task_content', $task->getContent())
            ->fillField('task_title', 'Titre de la tâche')
            ->fillField('task_content', 'Contenu de la tâche')
            ->click('button')
            ->assertElementCount('div.card', 1)
            ->assertSeeIn('.card-title', 'Titre de la tâche')
            ->assertSeeIn('.card-text', 'Contenu de la tâche');
    }

    public function testUserShouldDeleteTask()
    {
        $user = UserFactory::new()->create();
        TaskFactory::new()->create(['owner' => $user, 'isDone' => false]);
        flush();

        $this->browser()
            ->actingAs($user)
            ->visit('/tasks')
            ->assertElementCount('div.card', 1)
            ->click('Supprimer')
            ->assertElementCount('div.card', 0)
            ->click('Consulter la liste des tâches terminées')
            ->assertElementCount('div.card', 0);
    }
}
