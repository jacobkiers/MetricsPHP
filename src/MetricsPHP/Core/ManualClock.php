<?php

namespace MetricsPHP\Core;

class ManualClock extends Clock
{
    protected $ticksInNanos = 0;

    public function addMillis($millis)
    {
        $this->ticksInNanos += $millis * 1000 * 1000;
    }

    public function addHours($hours)
    {
        $this->ticksInNanos += $hours * 3600 * 1000 * 1000 * 1000;
    }

    public function getTick()
    {
        return $this->ticksInNanos;
    }

    public function getTime()
    {
        return $this->getTimeInMilliseconds();
    }

    public function getTimeInMicroseconds()
    {
        return $this->ticksInNanos / 1000;
        ;
    }

    public function getTimeInMilliseconds()
    {
        return $this->ticksInNanos / 1000 / 1000;
    }

    public function getTimeInNanoseconds()
    {
        return $this->ticksInNanos;
    }

    public function getTimeInSeconds()
    {
        return $this->ticksInNanos / 1000 / 1000 / 1000;
    }
}