<?php

namespace App\Tests\Domain;

use App\Domain\DateRange;
use App\Domain\Task;
use App\Domain\TaskId;
use App\Domain\TaskName;
use App\Domain\TaskResume;
use PHPUnit\Framework\TestCase;

class TaskResumeTest extends TestCase
{
    private function getEmptyTaskResume(): TaskResume
    {
        $DateTimeStart = new \DateTime('2024-10-21 00:00:00');
        $DateTimeEnd = new \DateTime('2024-10-22 00:00:00');
        $dateRange = new DateRange($DateTimeStart, $DateTimeEnd);

        return new TaskResume($dateRange);
    }

    public function testEmptyTaskResumeShouldBeEmpty(): void
    {

        $emptyTaskResume = $this->getEmptyTaskResume();


        $this->assertEquals([], $emptyTaskResume->getResume());
    }

    public function testEmptyTaskResumeShouldHaveEmptyTotalTime(): void
    {
        $emptyTaskResume = $this->getEmptyTaskResume();

        $this->assertEquals(
            '00:00:00',
            $emptyTaskResume->getTotalTime()->format('H:i:s')
        );
    }

    public function testTaskResumeWithMultipleEntryShouldHaveCorrectTotalTime(): void
    {
        $emptyTaskResume = $this->getEmptyTaskResume();

        $DateTimeStart = new \DateTime('2024-10-21 00:00:00');
        $DateTimeEnd = new \DateTime('2024-10-21 00:30:00');

        $task = new Task(
            new TaskId('0192a20f-0294-7c73-b246-f4cfdb48f4a2'),
            new TaskName('Test Task'),
            new DateRange($DateTimeStart, $DateTimeEnd)
        );

        $emptyTaskResume->addTask($task);
        $emptyTaskResume->addTask($task);

        $this->assertEquals(
            '01:00:00',
            $emptyTaskResume->getTotalTime()->format('H:i:s')
        );
    }

    public function testTaskResumeWithMultipleEntryShouldHaveCorrectResume(): void
    {
        $emptyTaskResume = $this->getEmptyTaskResume();

        $dateTimeStart = new \DateTime('2024-10-21 00:00:00');
        $dateTimeEnd = new \DateTime('2024-10-21 00:30:00');

        $task = new Task(
            new TaskId('0192a20f-0294-7c73-b246-f4cfdb48f4a2'),
            new TaskName('Test Task'),
            new DateRange($dateTimeStart, $dateTimeEnd)
        );

        $emptyTaskResume->addTask($task);
        $emptyTaskResume->addTask($task);

        $this->assertEquals(
            "01:00:00",
            $emptyTaskResume->getResume()["Test Task"]->format('H:i:s')
        );
    }


    public function testTaskResumeOnlyCountTimeInTheRange(): void
    {
        $emptyTaskResume = $this->getEmptyTaskResume();

        $DateTimeStart = new \DateTime('2024-10-20 22:00:00');
        $DateTimeEnd = new \DateTime('2024-10-21 00:30:00');

        $task = new Task(
            new TaskId('0192a20f-0294-7c73-b246-f4cfdb48f4a2'),
            new TaskName('Test Task'),
            new DateRange($DateTimeStart, $DateTimeEnd)
        );

        $emptyTaskResume->addTask($task);
        $emptyTaskResume->addTask($task);

        $this->assertEquals(
            '01:00:00',
            $emptyTaskResume->getTotalTime()->format('H:i:s')
        );
        $this->assertEquals(
            "01:00:00",
            $emptyTaskResume->getResume()["Test Task"]->format('H:i:s')
        );
    }

}
