<?php

namespace App\Aplication;

use App\Domain\DateRange;
use App\Domain\NoActiveTask;
use App\Domain\Task;
use App\Domain\TaskAlreadyStopped;
use App\Domain\TaskRepository;
use App\Domain\TimeRepository;
use App\Domain\TrackerAction;

class AvailableTrackerAction
{
    private TaskRepository $taskRepository;

    public function __construct(TaskRepository $taskRepository, )
    {
        $this->taskRepository = $taskRepository;
    }


    public function execute():TrackerAction{
        $task = $this->taskRepository->getCurrentTask();
        if(is_null($task)){
            return TrackerAction::Start;
        }
        return $task->isRunning()?TrackerAction::Stop:TrackerAction::Start;
    }

}