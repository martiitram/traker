<?php

namespace Aplication;

use App\Aplication\StartTask;
use App\Domain\DateRange;
use App\Domain\IdGeneratorRepository;
use App\Domain\StartTaskAlreadyExistException;
use App\Domain\Task;
use App\Domain\TaskId;
use App\Domain\TaskName;
use App\Domain\TaskRepository;
use App\Domain\TimeRepository;
use Mockery;
use PHPUnit\Framework\TestCase;

class StartTaskTest extends TestCase
{
    public function tearDown(): void
    {
        Mockery::close(); // Clean up Mockery after the test
    }

    public function testStartTaskThrowStartTaskAlreadyExistException()
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
        $IdGeneratorRepository = Mockery::mock(IdGeneratorRepository::class);
        $startTask = new StartTask(
            $taskRepository,
            $timeRepository,
            $IdGeneratorRepository
        );

        $this->expectException(StartTaskAlreadyExistException::class);

        $startTask->execute('test');

    }

    public function testStartTaskHappyPath()
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
        $timeRepository->shouldReceive('getCurrentDateTime')->once()
            ->andReturn(new \DateTime('2024-10-21 00:00:00'));
        $IdGeneratorRepository = Mockery::mock(IdGeneratorRepository::class);
        $IdGeneratorRepository->shouldReceive('generateId')->once()
            ->andReturn('0192a40f-0224-7c71-b246-f4cfdb48f4a3');

        $taskRepository->shouldReceive('save')->once();

        $startTask = new StartTask(
            $taskRepository,
            $timeRepository,
            $IdGeneratorRepository
        );

        $startTask->execute('test');

        $this->expectNotToPerformAssertions();
    }

    public function testStartTaskHappyPathWithNoCurrentTask()
    {

        $taskRepository = Mockery::mock(TaskRepository::class);
        $taskRepository->shouldReceive('getCurrentTask')->once()
            ->andReturn(null);
        $timeRepository = Mockery::mock(TimeRepository::class);
        $timeRepository->shouldReceive('getCurrentDateTime')->once()
            ->andReturn(new \DateTime('2024-10-21 00:00:00'));
        $IdGeneratorRepository = Mockery::mock(IdGeneratorRepository::class);
        $IdGeneratorRepository->shouldReceive('generateId')->once()
            ->andReturn('0192a40f-0224-7c71-b246-f4cfdb48f4a3');

        $taskRepository->shouldReceive('save')->once();

        $startTask = new StartTask(
            $taskRepository,
            $timeRepository,
            $IdGeneratorRepository
        );

        $startTask->execute('test');

        $this->expectNotToPerformAssertions();
    }
}
