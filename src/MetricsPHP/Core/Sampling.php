<?php

namespace MetricsPHP\Core;

use MetricsPHP\Stats\Snapshot;

/**
 * An object which samples values.
 */
interface Sampling
{
    /**
     * Returns a snapshot fo the values.
     *
     * @return Snapshot
     */
    public function getSnapshot();
}