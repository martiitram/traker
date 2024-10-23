<?php

namespace App\Tests\Domain;

use App\Domain\DateRange;
use App\Domain\DateRangeWithStartAfterEnd;
use PHPUnit\Framework\TestCase;

final class DateRangeTest extends TestCase
{
    public function testHaveEndShouldReturnTrueIfEndIsNotSet(): void
    {
        $dateRange = new DateRange(new \DateTime());
        $this->assertEquals(false, $dateRange->haveEnd());
    }

    public function testHaveEndShouldReturnFasleIfEndIsSet(): void
    {
        $dateRange = new DateRange(new \DateTime(), new \DateTime());
        $this->assertEquals(true, $dateRange->haveEnd());
    }

    public function testGetTodayDateRangeShouldReturnDateRangeOfToday(): void
    {
        $specificDateTime = new \DateTime('2024-10-21 12:30:00');
        $expectedDateTimeStart = new \DateTime('2024-10-21 00:00:00');
        $expectedDateTimeEnd = new \DateTime('2024-10-22 00:00:00');


        $dateRange = DateRange::getTodayDateRange($specificDateTime);


        $this->assertEquals($expectedDateTimeStart, $dateRange->getStart());
        $this->assertEquals($expectedDateTimeEnd, $dateRange->getEnd());
    }

    public function testGetTodayDateRangeShouldReturnDateRangeOfTodayIfAre_00_00_00(): void
    {
        $specificDateTime = new \DateTime('2024-10-21 00:00:00');
        $expectedDateTimeStart = new \DateTime('2024-10-21 00:00:00');
        $expectedDateTimeEnd = new \DateTime('2024-10-22 00:00:00');


        $dateRange = DateRange::getTodayDateRange($specificDateTime);


        $this->assertEquals($expectedDateTimeStart, $dateRange->getStart());
        $this->assertEquals($expectedDateTimeEnd, $dateRange->getEnd());
    }

    public function testOverlappingTimeShouldCalculateCorrectlyWithTheSameRange()
    {
        $timeStart = new \DateTime('2024-10-21 01:00:00');
        $timeEnd = new \DateTime('2024-10-23 02:00:00');
        $dateRange = new DateRange($timeStart, $timeEnd);

        $overlappedDateRange = $dateRange->overlappingTime($dateRange);


        $this->assertEquals($timeStart, $overlappedDateRange->getStart());
        $this->assertEquals($timeEnd, $overlappedDateRange->getEnd());
    }

    public function testOverlappingTimeShouldCalculateCorrectlyWithDifferentRange()
    {
        $timeStart1 = new \DateTime('2024-10-21 01:00:00');
        $timeEnd1 = new \DateTime('2024-10-23 03:00:00');
        $dateRange1 = new DateRange($timeStart1, $timeEnd1);

        $timeStart2 = new \DateTime('2024-10-21 02:00:00');
        $timeEnd2 = new \DateTime('2024-10-23 04:00:00');
        $dateRange2 = new DateRange($timeStart2, $timeEnd2);


        $overlappedDateRange = $dateRange1->overlappingTime($dateRange2);
        $overlappedDateRange2 = $dateRange2->overlappingTime($dateRange1);


        $this->assertEquals($timeStart2, $overlappedDateRange->getStart());
        $this->assertEquals($timeEnd1, $overlappedDateRange->getEnd());
        //verify it work both ways
        $this->assertEquals($timeStart2, $overlappedDateRange2->getStart());
        $this->assertEquals($timeEnd1, $overlappedDateRange2->getEnd());
    }

    public function testOverlappingTimeShouldCalculateCorrectlyWithNonOverlappingRange()
    {
        $timeStart1 = new \DateTime('2024-10-21 01:00:00');
        $timeEnd1 = new \DateTime('2024-10-21 03:00:00');
        $dateRange1 = new DateRange($timeStart1, $timeEnd1);

        $timeStart2 = new \DateTime('2024-10-21 05:00:00');
        $timeEnd2 = new \DateTime('2024-10-21 06:00:00');
        $dateRange2 = new DateRange($timeStart2, $timeEnd2);


        $overlappedDateRange = $dateRange1->overlappingTime($dateRange2);


        $this->assertEquals($overlappedDateRange->getEnd(), $overlappedDateRange->getStart());
    }

    public function testElapsedDatetimeShouldCalculateCorrectly(): void
    {
        $timeStart1 = new \DateTime('2024-10-21 01:00:00');
        $timeEnd1 = new \DateTime('2024-10-22 03:00:00');
        $dateRange1 = new DateRange($timeStart1, $timeEnd1);
        $expectedDateInterval = new \DateInterval('P1DT2H');


        $elapsedTime = $dateRange1->elapsedDatetime();

        $this->assertEquals(
            $expectedDateInterval->format('Y-m-d H:i:s'),
            $elapsedTime->format('Y-m-d H:i:s')
        );
    }

    public function testElapsedDatetimeShouldCalculateCorrectlyEmptyRanges()
    {
        $time = new \DateTime('2024-10-21 01:00:00');
        $dateRange1 = new DateRange($time, $time);
        $expectedDateInterval = new \DateInterval('PT0H');


        $elapsedTime = $dateRange1->elapsedDatetime();

        $this->assertEquals(
            $expectedDateInterval->format('Y-m-d H:i:s'),
            $elapsedTime->format('Y-m-d H:i:s')
        );
    }

    public function testCreateDateRangeValidateStartBeforeEnd()
    {
        $timeStart = new \DateTime('2024-10-23 01:00:00');
        $timeEnd = new \DateTime('2024-10-22 03:00:00');


        $this->expectException(DateRangeWithStartAfterEnd::class);


        new DateRange($timeStart, $timeEnd);
    }

    public function testModifyActionsValidateStartBeforeEnd()
    {
        $timeStart = new \DateTime('2024-10-23 01:00:00');
        $timeEnd = new \DateTime('2024-10-24 03:00:00');
        $newTimeEnd = new \DateTime('2024-10-22 03:00:00');
        $dateRange = new DateRange($timeStart, $timeEnd);


        $this->expectException(DateRangeWithStartAfterEnd::class);

        $dateRange->setEnd($newTimeEnd);
    }

    public function testSetEndTimeIfNullActuallySetEndTimeIfNull()
    {
        $timeStart = new \DateTime('2024-10-23 01:00:00');
        $newTimeEnd = new \DateTime('2024-10-24 03:00:00');
        $dateRange = new DateRange($timeStart);


        $dateRange->setEndIfNull($newTimeEnd);


        $this->assertEquals(
            $newTimeEnd->format('Y-m-d H:i:s'),
            $dateRange->getEnd()->format('Y-m-d H:i:s')
        );
    }


    public function testSetEndTimeIfNullDoesNothingIfEndTimeIsSet()
    {
        $timeStart = new \DateTime('2024-10-23 01:00:00');
        $timeEnd = new \DateTime('2024-10-24 03:00:00');
        $newTimeEnd = new \DateTime('2024-10-22 03:00:00');
        $dateRange = new DateRange($timeStart, $timeEnd);


        $dateRange->setEndIfNull($newTimeEnd);


        $this->assertEquals(
            $timeEnd->format('Y-m-d H:i:s'),
            $dateRange->getEnd()->format('Y-m-d H:i:s')
        );
    }
}
