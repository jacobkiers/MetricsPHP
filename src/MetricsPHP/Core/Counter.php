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
     * @param integer $inc Defaults to 1
     *
     * @return integer The new count
     */
    public function inc($inc = 1)
    {
        $this->count += $inc;
        return $this->count;
    }

    /**
     * Decrement the counter.
     *
     * @param integer $dec Defaults to 1
     *
     * @return integer The new count
     */
    public function dec($dec = 1)
    {
        $this->count -= $dec;
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
