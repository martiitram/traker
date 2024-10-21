<?php

namespace App\Controller;

use App\Aplication\AvailableTrackerAction;
use App\Aplication\ListTasks;
use App\Aplication\StartTask;
use App\Aplication\StopTask;
use App\Aplication\TaskResume;
use App\Domain\NoActiveTask;
use App\Domain\StartTaskAlredyExistException;
use App\Domain\TaskAlreadyStopped;
use App\Repository\BasicTimeRepository;
use App\Repository\SymfonyTaskRepository;
use App\Repository\UuidV7IdGeneratorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class TrackerController extends AbstractController
{
    #[Route('/tracker', name: 'app_tracker', methods: ['GET', 'POST'])]
    public function index(Request $request, SymfonyTaskRepository $taskRepository): Response
    {
        $vars = [];
        $listTasks = new ListTasks(
            $taskRepository,
            new BasicTimeRepository()
        );
        $vars['tasks'] = $listTasks->getTodayTasks();


        $availableTrackerAction = new AvailableTrackerAction($taskRepository);
        $vars['action'] = $availableTrackerAction->execute();

        if ($request->query->get('error_message')) {
            $vars['error_message'] = $request->query->get('error_message');
        }
        if ($request->query->get('success_message')) {
            $vars['success_message'] = $request->query->get('success_message');
        }
        return $this->render('tracker/index.html.twig', $vars);
    }

    #[Route('/tracker/start', name: 'app_start_task', methods: ['GET', 'POST'])]
    public function start(Request $request, SymfonyTaskRepository $taskRepository): Response
    {
        $startTask = new StartTask(
            $taskRepository,
            new BasicTimeRepository(),
            new UuidV7IdGeneratorRepository()
        );
        $vars = [];

        try {
            $name = $request->get('task_name');
            $startTask->execute($name);
            $vars['success_message'] = 'Task Started';
        } catch (StartTaskAlredyExistException $e) {
            $vars['error_message'] = $e->getMessage();
        }
        return $this->redirectToRoute('app_tracker', $vars, Response::HTTP_SEE_OTHER);
    }

    #[Route('/tracker/stop', name: 'app_stop_task', methods: ['GET', 'POST'])]
    public function stop(SymfonyTaskRepository $taskRepository): Response
    {
        $startTask = new StopTask(
            $taskRepository,
            new BasicTimeRepository()
        );

        $vars = [];
        try {
            $startTask->execute();
            $vars['success_message'] = 'Task stopped';
        } catch (NoActiveTask|TaskAlreadyStopped $e) {
            $vars['error_message'] = $e->getMessage();
        }
        return $this->redirectToRoute('app_tracker',
            $vars, Response::HTTP_SEE_OTHER);
    }


    #[Route('/tracker/resume', name: 'app_task_resume', methods: ['GET', 'POST'])]
    public function resume(SymfonyTaskRepository $taskRepository): Response
    {
        $taskResume = new TaskResume(
            $taskRepository,
            new BasicTimeRepository()
        );

        $taskResume = $taskResume->getTodayResume();
        $resume = $taskResume->getResume();
        $totalTime = $taskResume->getTotalTime();
        return $this->render('tracker/resume.html.twig',
            [
                'resume' => $resume,
                'totalTime' => $totalTime,
            ]);
    }
}
