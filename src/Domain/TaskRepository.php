<?php

namespace App\Domain;

interface TaskRepository
{
    function getCurrentTask(): ?Task;

    public function save(Task $task);
    /**
     * @return Task[]
     */
    public function getList(DateRange $dateRange): array;
}