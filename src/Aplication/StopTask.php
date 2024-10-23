<?php

namespace App\Aplication;

use App\Domain\NoActiveTask;
use App\Domain\Task;
use App\Domain\TaskAlreadyStopped;
use App\Domain\TaskRepository;
use App\Domain\TimeRepository;

class StopTask
{
    private TaskRepository $taskRepository;
    private TimeRepository $timeRepository;

    public function __construct(TaskRepository $taskRepository, TimeRepository $timeRepository)
    {
        $this->taskRepository = $taskRepository;
        $this->timeRepository = $timeRepository;
    }


    /**
     * @throws TaskAlreadyStopped
     * @throws NoActiveTask
     */
    public function execute(): void
    {
        $task = $this->taskRepository->getCurrentTask();

        $this->verifyValidStoppingTaskGuard($task);

        $task->setEnd($this->timeRepository->getCurrentDateTime());
        $this->taskRepository->save($task);
    }

    /**
     * @throws TaskAlreadyStopped
     * @throws NoActiveTask
     */
    private function verifyValidStoppingTaskGuard(?Task $task): void
    {
        if (is_null($task)) {
            throw new NoActiveTask();
        }

        if (!$task->isRunning()) {
            throw new TaskAlreadyStopped($task);
        }
    }

}