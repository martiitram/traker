<?php

namespace App\Domain;


class Task
{
    private TaskId $id;
    private TaskName $name;
    private DateRange $dateRange;


    public function __construct(TaskId $task_id, TaskName $name, DateRange $dateRange)
    {
        $this->name = $name;
        $this->id = $task_id;
        $this->dateRange = $dateRange;
    }

    /**
     * @return TaskName
     */
    public function getName(): TaskName
    {
        return $this->name;
    }

    public function isRunning(): bool
    {
        return !$this->dateRange->haveEnd();
    }

    public function getStart(): \DateTime
    {
        return $this->dateRange->getStart();
    }

    public function getEnd(): ?\DateTime
    {
        return $this->dateRange->getEnd();
    }

    public function getId(): TaskId
    {
        return $this->id;
    }

    public function setEnd(\DateTime $dateTime): void
    {
        $this->dateRange->setEnd($dateTime);
    }

    public function getDateRange(): DateRange
    {
        return $this->dateRange;
    }

    public function setEndIfNull(\DateTime $dateTime): void
    {
        $this->dateRange->setEndIfNull($dateTime);
    }
}