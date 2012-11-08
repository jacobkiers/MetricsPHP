<?php

/**
 * An object which can produce statistical summaries.
 */
interface Summarizable
{
    /**
     * Returns the largest recorded value.
     *
     * @return float
     */
    public function getMax();

    /**
     * Returns the smallest recorded value.
     *
     * @return float
     */
    public function getMin();

    /**
     * Returns the arithmetic mean of all recorded values.
     *
     * @return float
     */
    public function getMean();

    /**
     * Returns the standard deviation of all recorded values.
     *
     * @return float
     */
    public function getStdDev();

    /**
     * Returns the sum of all recorded values.
     *
     * @return float
     */
    public function getSum();
}
