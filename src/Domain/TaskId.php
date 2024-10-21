<?php

namespace App\Domain;

class TaskId
{
    private $taskId;

    public function __construct(string $taskId)
    {
        $this->taskId = $taskId;
    }

    public function getTaskId(): string
    {
        return $this->taskId;
    }
}