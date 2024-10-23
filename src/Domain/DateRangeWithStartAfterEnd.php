<?php

namespace App\Domain;

use Exception;

class DateRangeWithStartAfterEnd extends Exception
{
    public function __construct(\DateTime $start,\DateTime $end)
    {
        $message = "The DateRange have invalid dateTimes correlation start:" . $start->format('Y-m-d H:i:s') .
            ", end:" . $end->format('Y-m-d H:i:s') ;
        parent::__construct($message);
    }

    // custom string representation of object
    public function __toString()
    {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}