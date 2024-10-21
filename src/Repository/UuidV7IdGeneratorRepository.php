<?php

namespace App\Repository;

use App\Domain\IdGeneratorRepository;
use Symfony\Component\Uid\Uuid;

class UuidV7IdGeneratorRepository implements IdGeneratorRepository
{

    function generateId(): string
    {
        return Uuid::v7()->toString();
    }
}