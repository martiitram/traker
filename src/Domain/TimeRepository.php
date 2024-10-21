<?php

namespace App\Domain;

interface TimeRepository
{
    function getCurrentDateTime(): \DateTime;
}