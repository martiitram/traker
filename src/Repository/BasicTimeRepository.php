<?php

namespace App\Repository;

use App\Domain\TimeRepository;

class BasicTimeRepository implements TimeRepository
{

    function getCurrentDateTime(): \DateTime
    {
        return new \DateTime();
    }
}