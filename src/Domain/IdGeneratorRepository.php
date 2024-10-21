<?php

namespace App\Domain;

interface IdGeneratorRepository
{
    function generateId(): string;
}