<?php

namespace App\Aplication;

use App\Domain\DateRange;
use App\Domain\TaskRepository;
use App\Domain\TimeRepository;

class TaskResume
{
    private TaskRepository $taskRepository;
    private TimeRepository $timeRepository;

    public function __construct(TaskRepository $taskRepository, TimeRepository $timeRepository)
    {
        $this->taskRepository = $taskRepository;
        $this->timeRepository = $timeRepository;
    }


    public function getTodayResume(): \App\Domain\TaskResume
    {
        $listTasks = new ListTasks($this->taskRepository, $this->timeRepository);
        $tasks = $listTasks->getTodayTasks();

        $now = $this->timeRepository->getCurrentDateTime();
        $dateRange = DateRange::getTodayDateRange($now);

        $taskResume= new \App\Domain\TaskResume($dateRange);
        foreach ($tasks as $task){
            $task->setEndIfNull($now);
            $taskResume->addTask($task);
        }
        return $taskResume;
    }
}