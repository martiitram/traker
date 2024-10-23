<?php

namespace App\Tests\Domain;

use App\Domain\DateRange;
use App\Domain\Task;
use App\Domain\TaskId;
use App\Domain\TaskName;
use PHPUnit\Framework\TestCase;

class TaskTest extends TestCase
{
    public function testARunningTaskShouldKnowIt(){
        $DateTimeStart = new \DateTime('2024-10-21 00:00:00');

        $task = new Task(
            new TaskId('0192a20f-0294-7c73-b246-f4cfdb48f4a2'),
            new TaskName('Test Task'),
            new DateRange($DateTimeStart)
        );


        $this->assertEquals(true,$task->isRunning());
    }

    public function testAEndedTaskShouldKnowIt(){
        $DateTimeStart = new \DateTime('2024-10-21 00:00:00');
        $DateTimeEnd = new \DateTime('2024-10-21 00:00:00');

        $task = new Task(
            new TaskId('0192a20f-0294-7c73-b246-f4cfdb48f4a2'),
            new TaskName('Test Task'),
            new DateRange($DateTimeStart, $DateTimeEnd)
        );

        $this->assertEquals(false,$task->isRunning());

    }
}
