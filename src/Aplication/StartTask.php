<?php

namespace App\Aplication;

use App\Domain\DateRange;
use App\Domain\IdGeneratorRepository;
use App\Domain\StartTaskAlreadyExistException;
use App\Domain\Task;
use App\Domain\TaskId;
use App\Domain\TaskName;
use App\Domain\TaskRepository;
use App\Domain\TimeRepository;

class StartTask
{
    private TaskRepository $taskRepository;
    private TimeRepository $timeRepository;
    private IdGeneratorRepository $idGeneratorRepository;

    public function __construct(TaskRepository $taskRepository, TimeRepository $timeRepository, IdGeneratorRepository $idGeneratorRepository)
    {
        $this->taskRepository = $taskRepository;
        $this->timeRepository = $timeRepository;
        $this->idGeneratorRepository = $idGeneratorRepository;

    }

    /**
     * @throws StartTaskAlreadyExistException
     */
    public function execute(string $name): void
    {
        $task = $this->taskRepository->getCurrentTask();
        $this->noCurrentActiveTaskGuard($task);
        $task = new Task(
            new TaskId($this->idGeneratorRepository->generateId()),
            new TaskName($name),
            new DateRange($this->timeRepository->getCurrentDateTime())
        );
        $this->taskRepository->save($task);
    }

    /**
     * @throws StartTaskAlreadyExistException
     */
    private function noCurrentActiveTaskGuard(?Task $task): void
    {
        if (!is_null($task) && $task->isRunning()) {
            throw new StartTaskAlreadyExistException($task);
        }
    }

}