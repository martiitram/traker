<?php

namespace App\Entity;

use App\Repository\SymfonyTaskRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Uid\UuidV7;

#[ORM\Entity(repositoryClass: SymfonyTaskRepository::class)]
class Task
{
    #[ORM\Id]
    #[ORM\Column(type: "uuid", unique: true)]
    private Uuid $id;
    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $start = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $end = null;

    public static function generateByDomainTask(\App\Domain\Task $domain_task): self
    {
        $task = new self();
        $task->id = UuidV7::fromString($domain_task->getId()->getTaskId());
        $task->fillByDomainTask($domain_task);
        return $task;
    }

    public function fillByDomainTask(\App\Domain\Task $domain_task): void{
        $this->setName($domain_task->getName()->name());
        $this->setStart($domain_task->getStart());
        $this->setEnd($domain_task->getEnd());
    }



    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getStart(): \DateTimeInterface
    {
        return $this->start;
    }

    public function setStart(\DateTimeInterface $start): static
    {
        $this->start = $start;

        return $this;
    }

    public function getEnd(): ?\DateTimeInterface
    {
        return $this->end;
    }

    public function setEnd(?\DateTimeInterface $end): static
    {
        $this->end = $end;

        return $this;
    }
}
