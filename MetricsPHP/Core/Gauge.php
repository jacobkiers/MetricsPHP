<?php

namespace MetricsPHP\Core;

abstract class Gauge implements Metric
{
    /**
     * Returns the metrics current value
     *
     * @return mixed
     */
    abstract public function getValue();
}