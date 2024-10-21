<?php

namespace App\Domain;

use Exception;

class NoActiveTask extends Exception
{
    public function __construct()
    {
        $message = "There is no running task";
        parent::__construct($message);
    }

    // custom string representation of object
    public function __toString()
    {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}