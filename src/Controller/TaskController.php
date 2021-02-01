<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class TaskController extends AbstractController
{
    /**
     * @Route("/tasks", name="task_list")
     */
    public function listTasks(TaskRepository $taskRepository)
    {
        return $this->render('task/list.html.twig', [
            'tasks' => $taskRepository->findAll()
        ]);
    }

    /**
     * @Route("/tasks/create", name="task_create")
     */
    public function createTask(Request $request)
    {
        $task = new Task;
        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($task);
            $em->flush();

            $this->addFlash('success', 'La tâche a bien été ajoutée.');

            return $this->redirectToRoute('task_list');
        }

        return $this->render('task/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/tasks/{id}/edit", name="task_edit")
     */
    public function editTask(Task $task, Request $request)
    {
        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'La tâche a bien été modifiée.');

            return $this->redirectToRoute('task_list');
        }

        return $this->render('task/edit.html.twig', [
            'form' => $form->createView(),
            'task' => $task,
        ]);
    }

    /**
     * @Route("/tasks/{id}/toggle", name="task_toggle")
     */
    public function toggleTask(Task $task)
    {
        $task->toggle(!$task->isDone());
        $this->getDoctrine()->getManager()->flush();

        $this->addFlash('success', sprintf('La tâche %s a bien été marquée comme faite.', $task->getTitle()));

        return $this->redirectToRoute('task_list');
    }

    /**
     * @Route("/tasks/{id}/delete", name="task_delete")
     */
    public function deleteTask(Task $task, UserRepository $userRepository)
    {
        if (
            $task->getOwner() !== $userRepository->getTheAnonymousUser()
            && $this->getUser() != $task->getOwner()
        ) {
            return $this->restrictDeletionToOwner();
        }

        if (
            $task->getOwner() === $userRepository->getTheAnonymousUser()
            && !$this->getUser()
            or !$this->getUser()->isAdmin()
        ) {
            return $this->restrictDeletionToAdmin();
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($task);
        $em->flush();

        $this->addFlash('success', 'La tâche a bien été supprimée.');

        return $this->redirectToRoute('task_list');
    }

    private function restrictDeletionToOwner()
    {
        $this->addFlash('error', 'Vous ne pouvez supprimer une tâche qui ne vous appartient pas.');

        return $this->redirectToRoute('task_list');
    }

    private function restrictDeletionToAdmin()
    {
        $this->addFlash('error', 'Cette tâche ne peut être supprimée que par un administrateur.');

        return $this->redirectToRoute('task_list');
    }
}
