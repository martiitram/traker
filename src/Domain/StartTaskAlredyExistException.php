<?php

namespace App\Domain;

use Exception;

class StartTaskAlredyExistException extends Exception
{
    public function __construct(Task $existingTask)
    {
        $message = "The task " . $existingTask->getName()->name() . " is alredy running";
        parent::__construct($message);
    }

    // custom string representation of object
    public function __toString()
    {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}