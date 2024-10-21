<?php

namespace App\Domain;

use Exception;

class TaskAlreadyStopped extends Exception
{
    public function __construct(Task $task)
    {
        $message = "The task " . $task->getName()->name() . "is already stopped";
        parent::__construct($message);
    }

    // custom string representation of object
    public function __toString()
    {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}