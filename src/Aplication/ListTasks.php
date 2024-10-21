<?php

namespace App\Aplication;

use App\Domain\DateRange;
use App\Domain\TaskRepository;
use App\Domain\TimeRepository;

class ListTasks
{
    private TaskRepository $taskRepository;
    private TimeRepository $timeRepository;

    public function __construct(TaskRepository $taskRepository, TimeRepository $timeRepository)
    {
        $this->taskRepository = $taskRepository;
        $this->timeRepository = $timeRepository;
    }


    public function getTodayTasks(){
        $now = $this->timeRepository->getCurrentDateTime();
        $dateRange = DateRange::getTodayDateRange($now);
        $tasks = $this->taskRepository->getList($dateRange);
        return $tasks;
    }
}