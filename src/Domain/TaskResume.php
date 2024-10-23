<?php

namespace App\Domain;


class TaskResume
{
    private DateRange $dateRange;

    public function __construct(DateRange $dateRange)
    {
        $this->dateRange = $dateRange;
    }

    // $resume['task_name]=timeSpend
    private $resume = [];

    public function addTask(Task $task)
    {
        $this->resume[$task->getName()->name()] ??= new \DateTime('00:00:00');
        $overlappedTime = $this->dateRange->overlappingTime($task->getDateRange());
        $elapsedTime = $overlappedTime->elapsedDatetime();
        $this->resume[$task->getName()->name()]->add($elapsedTime);
    }

    public function getResume(): array
    {
        return $this->resume;
    }

    public function getTotalTime(): \DateTime
    {
        return array_reduce($this->resume, function (\DateTime $carry, \DateTime $dateTime) {
            $startOfDay = new \DateTime('00:00:00');
            $interval = $startOfDay->diff($dateTime);
            return $carry->add($interval);
        }, new \DateTime('00:00:00'));
    }
}