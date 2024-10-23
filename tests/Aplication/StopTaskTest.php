<?php

namespace App\Tests\Aplication;

use App\Aplication\StopTask;
use App\Domain\DateRange;
use App\Domain\IdGeneratorRepository;
use App\Domain\NoActiveTask;
use App\Domain\StopTaskAlreadyExistException;
use App\Domain\Task;
use App\Domain\TaskAlreadyStopped;
use App\Domain\TaskId;
use App\Domain\TaskName;
use App\Domain\TaskRepository;
use App\Domain\TimeRepository;
use Mockery;
use PHPUnit\Framework\TestCase;

class StopTaskTest extends TestCase
{
    public function tearDown(): void
    {
        Mockery::close(); // Clean up Mockery after the test
    }

    public function testStopTaskThrowNoActiveTaskException()
    {


        $taskRepository = Mockery::mock(TaskRepository::class);
        $taskRepository->shouldReceive('getCurrentTask')->once()
            ->andReturn(null);
        $timeRepository = Mockery::mock(TimeRepository::class);
        $StopTask = new StopTask(
            $taskRepository,
            $timeRepository
        );

        $this->expectException(NoActiveTask::class);

        $StopTask->execute();

    }
    public function testStopTaskThrowTaskAlreadyStoppedException()
    {
        $DateTimeStart = new \DateTime('2024-10-21 00:00:00');
        $DateTimeEnd = new \DateTime('2024-10-21 00:00:00');

        $currentTask = new Task(
            new TaskId('0192a20f-0294-7c73-b246-f4cfdb48f4a2'),
            new TaskName('Test Task'),
            new DateRange($DateTimeStart, $DateTimeEnd)
        );

        $taskRepository = Mockery::mock(TaskRepository::class);
        $taskRepository->shouldReceive('getCurrentTask')->once()
            ->andReturn($currentTask);
        $timeRepository = Mockery::mock(TimeRepository::class);
        $StopTask = new StopTask(
            $taskRepository,
            $timeRepository
        );

        $this->expectException(TaskAlreadyStopped::class);

        $StopTask->execute();

    }

    public function testStopTaskHappyPath()
    {
        $DateTimeStart = new \DateTime('2024-10-21 00:00:00');

        $currentTask = new Task(
            new TaskId('0192a20f-0294-7c73-b246-f4cfdb48f4a2'),
            new TaskName('Test Task'),
            new DateRange($DateTimeStart)
        );


        $taskRepository = Mockery::mock(TaskRepository::class);
        $taskRepository->shouldReceive('getCurrentTask')->once()
            ->andReturn($currentTask);
        $timeRepository = Mockery::mock(TimeRepository::class);
        $timeRepository->shouldReceive('getCurrentDateTime')->once()
            ->andReturn(new \DateTime('2024-10-21 00:00:00'));

        $taskRepository->shouldReceive('save')->once();

        $StopTask = new StopTask(
            $taskRepository,
            $timeRepository
        );

        $StopTask->execute();

        $this->expectNotToPerformAssertions();
    }
}
