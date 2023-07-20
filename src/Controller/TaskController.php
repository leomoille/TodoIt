<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TaskController extends AbstractController
{
    #[Route('/tasks', name: 'task_list')]
    public function listAction(TaskRepository $taskRepository): Response
    {
        return $this->render('task/list.html.twig', [
            'tasks' => $taskRepository->findBy(['owner' => $this->getUser(), 'isDone' => false]),
            'orphanedTasks' => $taskRepository->findBy(['owner' => null]),
        ]);
    }

    #[Route('/tasks/create', name: 'task_create')]
    public function createAction(Request $request, EntityManagerInterface $manager): RedirectResponse|Response
    {
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $manager;

            $task
                ->setCreatedAt(new \DateTimeImmutable())
                ->setIsDone(false)
                ->setOwner($this->getUser());

            $em->persist($task);
            $em->flush();

            $this->addFlash('success', 'La tâche a été bien été ajoutée.');

            return $this->redirectToRoute('task_list');
        }

        return $this->render('task/create.html.twig', ['form' => $form->createView()]);
    }

    #[Route('/tasks/done', name: 'task_done')]
    public function listDone(TaskRepository $taskRepository): Response
    {
        $tasks = $taskRepository->findBy(['owner' => $this->getUser(), 'isDone' => true]);

        return $this->render('task/done.html.twig', [
            'tasks' => $tasks,
        ]);
    }

    #[Route('/tasks/{id}/edit', name: 'task_edit')]
    public function editAction(Task $task, Request $request, EntityManagerInterface $manager): RedirectResponse|Response
    {
        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($task);
            $manager->flush();

            $this->addFlash('success', 'La tâche a bien été modifiée.');

            return $this->redirectToRoute('task_list');
        }

        return $this->render('task/edit.html.twig', [
            'form' => $form->createView(),
            'task' => $task,
        ]);
    }

    #[Route('/tasks/{id}/toggle', name: 'task_toggle')]
    public function toggle(Task $task, EntityManagerInterface $manager): RedirectResponse
    {
        $message = '';
        if ($task->isDone()) {
            $task->setIsDone(false);
            $message = 'La tâche a bien été marquée comme en cours.';
        } else {
            $task->setIsDone(true);
            $message = 'La tâche a bien été marquée comme terminée.';
        }

        $manager->persist($task);
        $manager->flush();

        $this->addFlash('success', $message);

        return $this->redirectToRoute('task_list');
    }

    #[Route('/tasks/{id}/delete', name: 'task_delete')]
    public function delete(Task $task, EntityManagerInterface $manager): RedirectResponse
    {
        $manager->remove($task);
        $manager->flush();

        $this->addFlash('success', 'La tâche a bien été supprimée.');

        return $this->redirectToRoute('task_list');
    }
}
