<?php

namespace MetricsPHP\Core;

class Counter implements Metric
{
    /**
     * The count
     *
     * @var integer
     */
    private $count = 0;

    /**
     * Increment the counter.
     *
     * @param integer $n Defaults to 1
     *
     * @return integer The new count
     */
    public function inc($n = 1)
    {
        $this->count += $n;
        return $this->count;
    }

    /**
     * Decrement the counter.
     *
     * @param integer $n Defaults to 1
     *
     * @return integer The new count
     */
    public function dec($n = 1)
    {
        $this->count -= $n;
        return $this->count;
    }

    /**
     * Returns the counter's current value.
     *
     * @return integer
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * Resets the counter to 0.
     */
    public function clear()
    {
        $this->count = 0;
    }
}
