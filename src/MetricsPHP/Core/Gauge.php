<?php

namespace MetricsPHP\Core;

class Gauge implements Metric
{
    protected $value;

    /**
     * Creates a new Gauge.
     *
     * @param mixed $value
     */
    public function __construct($value)
    {
        $this->value = $value;
    }

    /**
     * Returns the metrics current value
     *
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }
}
