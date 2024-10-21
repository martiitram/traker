<?php

namespace App\Domain;

use DateTime;

class DateRange
{
    private DateTime $start;
    private ?DateTime $end;

    public function __construct(DateTime $start, ?DateTime $end = null)
    {
        $this->start = $start;
        $this->end = $end;
    }

    public function haveEnd(): bool
    {
        return !is_null($this->end);
    }

    public function getStart(): DateTime
    {
        return $this->start;
    }

    public function getEnd(): ?DateTime
    {
        return $this->end;
    }

    public function setEndDate(DateTime $dateTime): void
    {
        $this->end = $dateTime;
    }

    public static function getTodayDateRange(DateTime $dateTime): DateRange
    {
        $dateTimeStart = (clone $dateTime)->setTime(0, 0, 0);
        $dateTimeEnd = (clone $dateTime)->setTime(0, 0, 0)->modify('+1 day');
        return new DateRange($dateTimeStart, $dateTimeEnd);
    }

    public function overlappingTime(DateRange $getDateRange): DateRange
    {
        $startOverlap = max($getDateRange->getStart(), $this->getStart());
        $endOverlap = min($getDateRange->getEnd(), $this->getEnd());
        if ($startOverlap >= $endOverlap) {
            //there are no overlap
            return new DateRange($startOverlap, $startOverlap);
        } else {
            return new DateRange($startOverlap, $endOverlap);
        }
    }

    public function elapsedDatetime(): \DateInterval|false
    {
        return $this->start->diff($this->end);
    }
}