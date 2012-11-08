<?php

namespace MetricsPHP\Core;

use MetricsPHP\Stats\Sample;

/**
 * A metric which calculates the distribution of a value.
 *
 * @see <a href="http://www.johndcook.com/standard_deviation.html">Accurately computing running
 * variance</a>
 */
class Histogram implements Metric, Sampling, Summarizable
{
    const DEFAULT_SAMPLE_SIZE = 2048;

    const DEFAULT_ALPHA = 0.015;

    /**
     * Contains the sample.
     *
     * @var Sample
     */
    protected $sample;

    protected $min;

    protected $max;

    protected $sum;

    protected $variance = array();

    protected $count;

    /**
     * Creates a new {@link Histogram} with the given sample.
     *
     * @param \MetricsPHP\Stats\Sample $sample
     */
    public function __construct(Sample $sample)
    {
        $this->sample = $sample;
    }

    protected function setMax($potentialMax)
    {
        if ($this->max < $potentialMax) {
            $this->max = $potentialMax;
        }
    }

    protected function setMin($potentialMin)
    {
        if ($this->min > $potentialMin) {
            $this->min = $potentialMin;
        }
    }

    protected function updateVariance($value)
    {
        $oldValues = $this->variance;
        $newValues = array(0, 0);

        if ($oldValues[0] == -1) {
            $newValues[0] = $value;
            $newValues[1] = 0;
        } else {
            $oldM = $oldValues[0];
            $oldS = $oldValues[1];

            $newM = $oldM + (($value - $oldM) / $this->getCount());
            $newS = $oldS + (($value - $oldM) * ($value - $newM));

            $newValues[0] = $newM;
            $newValues[1] = $newS;
        }
        $this->variance = $newValues;
    }

    public function clear()
    {
        $this->sample->clear();
        $this->count = 0;
        $this->max = 2147483647;
        $this->min = -2147483646;
        $this->sum = 0;
        $this->variance = array(-1, 0);
    }

    public function update($value)
    {
        $this->count++;
        $this->sample->update($value);
        $this->setMax($value);
        $this->setMin($value);
        $this->sum += $value;
        $this->updateVariance($value);
    }

    /**
     * Returns the number of recorded values.
     *
     * @return integer
     */
    public function getCount()
    {
        return $this->count;
    }

    public function getMax()
    {
        if ($this->getCount() > 0) {
            return (float) $this->max;
        }
        return 0.0;
    }

    public function getMin()
    {
        if ($this->getCount() > 0) {
            return (float) $this->min;
        }
        return 0.0;
    }

    public function getMean()
    {
        if ($this->count > 0) {
            return $this->sum / $this->getCount();
        }
        return 0.0;
    }

    public function getStdDev()
    {
        if ($this->getCount() > 0) {
            return sqrt($this->getVariance());
        }
        return 0.0;
    }

    public function getSum()
    {
        return (float) $this->sum;
    }

    public function getSnapshot()
    {
        return $this->sample->getSnapshot();
    }

    public function getVariance()
    {
        if ($this->getCount() <= 1) {
            return 0.0;
        }
        return $this->variance[1] / ($this->getCount() - 1);
    }
}