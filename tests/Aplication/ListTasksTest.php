<?php

namespace App\Tests\Aplication;

use App\Aplication\ListTasks;
use App\Domain\DateRange;
use App\Domain\Task;
use App\Domain\TaskId;
use App\Domain\TaskName;
use App\Domain\TaskRepository;
use App\Domain\TimeRepository;
use App\Domain\TrackerAction;
use Mockery;
use PHPUnit\Framework\TestCase;

class ListTasksTest extends TestCase
{
    public function tearDown(): void
    {
        Mockery::close(); // Clean up Mockery after the test
    }

    public function testEmptyListTasks()
    {
        $taskRepository = Mockery::mock(TaskRepository::class);
        $taskRepository->shouldReceive('getList')->once()
            ->andReturn([]);
        $timeRepository = Mockery::mock(TimeRepository::class);
        $timeRepository->shouldReceive('getCurrentDateTime')->once()
            ->andReturn(new \DateTime('2024-10-21 00:00:00'));

        $listTasks = new ListTasks($taskRepository, $timeRepository);
        $tasks = $listTasks->getTodayTasks();
        $this->assertEquals(
            [],
            $tasks
        );
    }

    public function testListTasks()
    {
        $DateTimeStart = new \DateTime('2024-10-21 00:00:00');
        $DateTimeEnd = new \DateTime('2024-10-21 00:00:00');

        $task = new Task(
            new TaskId('0192a20f-0294-7c73-b246-f4cfdb48f4a2'),
            new TaskName('Test Task'),
            new DateRange($DateTimeStart, $DateTimeEnd)
        );

        $taskRepository = Mockery::mock(TaskRepository::class);
        $taskRepository->shouldReceive('getList')->once()
            ->andReturn([$task]);
        $timeRepository = Mockery::mock(TimeRepository::class);
        $timeRepository->shouldReceive('getCurrentDateTime')->once()
            ->andReturn(new \DateTime('2024-10-21 00:00:00'));

        $listTasks = new ListTasks($taskRepository, $timeRepository);
        $tasks = $listTasks->getTodayTasks();
        $this->assertEquals(
            [$task],
            $tasks
        );
    }
}
