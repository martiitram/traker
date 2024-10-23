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
        $this->validateDateRange();
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

    public function setEnd(DateTime $dateTime): void
    {
        $this->end = $dateTime;
        $this->validateDateRange();
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
        $dateInterval = $this->end->diff($this->start);
        $dateInterval->invert = false;
        return $dateInterval;
    }

    private function validateDateRange(): void
    {
        if(!is_null($this->end) && $this->end < $this->start) {
            throw new DateRangeWithStartAfterEnd($this->start, $this->end);
        }
    }

    public function __toString(): string
    {
        return $this->start->format('Y-m-d/m/y H:i:s').'-'.$this->end->format('Y-m-d/m/y H:i:s');
    }

    public function setEndIfNull(DateTime $dateTime): void
    {
        if (is_null($this->end)) {
            $this->setEnd($dateTime);
        }
    }
}