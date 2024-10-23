<?php

namespace App\Aplication;

use App\Domain\TaskRepository;
use App\Domain\TrackerAction;

class GetCurrentTask
{
    private TaskRepository $taskRepository;

    public function __construct(TaskRepository $taskRepository, )
    {
        $this->taskRepository = $taskRepository;
    }


    public function execute(): ?\App\Domain\Task{
        $task = $this->taskRepository->getCurrentTask();

        return $task;
    }

}