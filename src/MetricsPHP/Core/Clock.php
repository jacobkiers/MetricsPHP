<?php

namespace MetricsPHP\Core;

class Clock
{

    /**
     * Returns the current time tick.
     *
     * @return integer time tick in nanoseconds
     */
    public function getTick()
    {
        return (int)(microtime(true)*1000000000);
    }

    /**
     * Returns the current time in milliseconds.
     *
     * @return integer Current time in milliseconds
     */
    public function getTimeInMilliseconds()
    {
        return (int)(microtime(true)*1000);
    }

    public function getTimeInSeconds()
    {
        return time();
    }

    public function getTimeInMicroseconds()
    {
        return (int)(microtime(true)*1000000);
    }

    public function getTimeInNanoseconds()
    {
        return $this->getTick();
    }

}