<?php

namespace MetricsPHP\Stats;

/*
 * A statistically representative sample of a data stream
 */
interface Sample
{

    /**
     * Clears all recorded values.
     */
    public function clear();

    /**
     * Returns the number of values recorded.
     *
     * @return the number of values recorded
     */
    public function size();

    /**
     * Adds a new recorded value to the sample.
     *
     * @param integer $value A new recorded value
     */
    public function update($value);

    /**
     * Returns a snapshot of the sample's values.
     *
     * @return Snapshot A snapshot of the sample's values
     */
    public function getSnapshot();
}