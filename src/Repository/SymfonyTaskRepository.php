<?php

namespace App\Repository;

use App\Domain\DateRange;
use App\Domain\TaskId;
use App\Domain\TaskName;
use App\Entity\Task;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Task>
 */
class SymfonyTaskRepository extends ServiceEntityRepository implements \App\Domain\TaskRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Task::class);
    }

    public function getCurrentTask(): ?\App\Domain\Task
    {
        $task = $this->createQueryBuilder('t')
            ->andWhere('t.end is NULL')
            ->orderBy('t.start', 'DESC')
            ->getQuery()
            ->getOneOrNullResult();

        if (is_null($task)) {
            return null;
        } else {
            return $this->transformTaskToDomainTask($task);
        }
    }

    public function save(\App\Domain\Task $domain_task): void
    {
        $task = $this->getEntityManager()->find(Task::class, $domain_task->getId()->getTaskId());
        if (!is_null($task)) {
            $task->fillByDomainTask($domain_task);
        } else {
            $task = Task::generateByDomainTask($domain_task);
        }
        $this->getEntityManager()->persist($task);
        $this->getEntityManager()->flush();
    }

    //    /**
    //     * @return Task[] Returns an array of Task objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('t.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Task
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
    /**
     * @return array|\App\Domain\Task[]
     */
    public function getList(DateRange $dateRange): array
    {
        $tasks = $this->createQueryBuilder('t')
            ->andWhere('t.start < :dateRangeEnd and (:dateRangeStart < t.end or t.end is NULL)')
            ->setParameter('dateRangeEnd', $dateRange->getEnd())
            ->setParameter('dateRangeStart', $dateRange->getStart())
            ->orderBy('t.start', 'ASC')
            ->getQuery()
            ->getResult();

        return array_map([$this, 'transformTaskToDomainTask'], $tasks);

    }

    private function transformTaskToDomainTask(Task $task): \App\Domain\Task
    {
        return new \App\Domain\Task(
            new taskId($task->getId()),
            new taskName($task->getName()),
            new DateRange($task->getStart(), $task->getEnd()),
        );

    }
}
