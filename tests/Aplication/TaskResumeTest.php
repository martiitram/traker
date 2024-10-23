<?php

namespace App\Tests\Aplication;

use App\Aplication\TaskResume;
use App\Domain\DateRange;
use App\Domain\Task;
use App\Domain\TaskId;
use App\Domain\TaskName;
use App\Domain\TaskRepository;
use App\Domain\TimeRepository;
use Mockery;
use PHPUnit\Framework\TestCase;

class TaskResumeTest extends TestCase
{
    public function tearDown(): void
    {
        Mockery::close(); // Clean up Mockery after the test
    }

    public function testTaskResume()
    {
        $DateTimeStart1 = new \DateTime('2024-10-21 00:00:00');
        $DateTimeEnd1 = new \DateTime('2024-10-21 00:10:00');
        $DateTimeStart2 = new \DateTime('2024-10-21 00:10:00');
        $DateTimeEnd2 = new \DateTime('2024-10-21 00:20:00');
        $DateTimeStart3 = new \DateTime('2024-10-21 00:20:00');
        $DateTimeEnd3 = new \DateTime('2024-10-21 00:30:00');

        $Task1 = new Task(
            new TaskId('0192a20f-0294-7c73-b246-f4cfdb48f4a2'),
            new TaskName('Test Task'),
            new DateRange($DateTimeStart1, $DateTimeEnd1)
        );

        $Task2 = new Task(
            new TaskId('0192a20f-0294-7c73-b246-f4cfdb48f4a3'),
            new TaskName('Test'),
            new DateRange($DateTimeStart2, $DateTimeEnd2)
        );
        $Task3 = new Task(
            new TaskId('0192a20f-0294-7c73-b246-f4cfdb48f4a4'),
            new TaskName('Test Task'),
            new DateRange($DateTimeStart3, $DateTimeEnd3)
        );

        $taskRepository = Mockery::mock(TaskRepository::class);
        $taskRepository->shouldReceive('getList')->once()
            ->andReturn([$Task1, $Task2, $Task3]);
        $timeRepository = Mockery::mock(TimeRepository::class);
        $timeRepository->shouldReceive('getCurrentDateTime')
            ->andReturn(new \DateTime('2024-10-21 00:00:00'));

        $taskResume = new TaskResume($taskRepository, $timeRepository);
        $resume = $taskResume->getTodayResume();

        $this->assertEquals(
            '00:20:00',
            $resume->getResume()['Test Task']->format('H:i:s')
        );

        $this->assertEquals(
            '00:10:00',
            $resume->getResume()['Test']->format('H:i:s')
        );

        $this->assertEquals(
            '00:30:00',
            $resume->getTotalTime()->format('H:i:s')
        );
    }
}
