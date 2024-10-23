<?php

namespace App\Tests\Aplication;

use App\Aplication\AvailableTrackerAction;
use App\Domain\DateRange;
use App\Domain\Task;
use App\Domain\TaskId;
use App\Domain\TaskName;
use App\Domain\TaskRepository;
use App\Domain\TrackerAction;
use Mockery;
use PHPUnit\Framework\TestCase;

class AvailableTrackerActionTest extends TestCase
{
    public function tearDown(): void
    {
        Mockery::close(); // Clean up Mockery after the test
    }

    public function testAvailableTrackerActionOnNoTask()
    {
        $taskRepository = Mockery::mock(TaskRepository::class);
        $taskRepository->expects('getCurrentTask')->once()
            ->andReturn(null);

        $availableTrackerAction = new AvailableTrackerAction($taskRepository);
        $action = $availableTrackerAction->execute();

        $this->assertEquals(
            $action,
            TrackerAction::Start
        );
    }

    public function testAvailableTrackerActionOnNoActiveTask()
    {
        $DateTimeStart = new \DateTime('2024-10-20 22:00:00');
        $DateTimeEnd = new \DateTime('2024-10-21 00:30:00');

        $task = new Task(
            new TaskId('0192a20f-0294-7c73-b246-f4cfdb48f4a2'),
            new TaskName('Test Task'),
            new DateRange($DateTimeStart, $DateTimeEnd)
        );

        $taskRepository = Mockery::mock(TaskRepository::class);
        $taskRepository->expects('getCurrentTask')->once()
            ->andReturn($task);

        $availableTrackerAction = new AvailableTrackerAction($taskRepository);
        $action = $availableTrackerAction->execute();

        $this->assertEquals(
            $action,
            TrackerAction::Start
        );
    }

    public function testAvailableTrackerActionOnActiveTask()
    {
        $DateTimeStart = new \DateTime('2024-10-20 22:00:00');

        $task = new Task(
            new TaskId('0192a20f-0294-7c73-b246-f4cfdb48f4a2'),
            new TaskName('Test Task'),
            new DateRange($DateTimeStart)
        );

        $taskRepository = Mockery::mock(TaskRepository::class);
        $taskRepository->expects('getCurrentTask')->once()
            ->andReturn($task);

        $availableTrackerAction = new AvailableTrackerAction($taskRepository);
        $action = $availableTrackerAction->execute();

        $this->assertEquals(
            $action,
            TrackerAction::Stop
        );
    }

}
