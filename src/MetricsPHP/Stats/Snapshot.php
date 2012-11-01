<?php

namespace MetricsPHP\Stats;

class Snapshot
{
    const MEDIAN_QUANTILE = 0.5;

    const P75_QUANTILE = 0.75;

    const P95_QUANTILE = 0.95;

    const P98_QUANTILE = 0.98;

    const P99_QUANTILE = 0.99;

    const P999_QUANTILE = 0.999;

    private $values = array();

    /**
     * Create a new {@link Snapshot} with the given values.
     *
     * @param array $values
     */
    public function __construct(array $values)
    {
        $this->values = $values;
    }

    /**
     * Returns the value at the given quantile.
     *
     * @param $quantile A given quantile, between 0 and 1
     *
     * @return float The value in the distribution at {@code quantile}
     */
    public function getValue($quantile)
    {
        if ($quantile < 0 || $quantile > 1) {
            throw new \InvalidArgumentException($quantile." is not between 0 and 1");
        }

        if (0 == $this->size()) {
            return 0.0;
        }

        $pos = $quantile * ($this->size() + 1);

        if ($pos < 1) {
            return $this->values[0];
        }

        if ($pos >= $this->size()) {
            return $this->values[$this->size() - 1];
        }

        $lower = $this->values[(int) $pos - 1];
        $upper = $this->values[(int) $pos];
        return ($lower + ($pos - floor($pos)) * ($upper - $lower));
    }

    /**
     * Returns the number of values in the snapshot.
     *
     * @return integer The number of values in the snapshot
     */
    public function size()
    {
        return count($this->values);
    }

    /**
     * Returns the median value in the distribution.
     *
     * @return float The median value in the distribution
     */
    public function getMedian()
    {
        return $this->getValue(self::MEDIAN_QUANTILE);
    }

    /**
     * Returns the value at the 75th percentile in the distribution.
     *
     * @return float The value at the 75th percentile in the distribution
     */
    public function get75thPercentile()
    {
        return $this->getValue(self::P75_QUANTILE);
    }

    /**
     * Returns the value at the 95th percentile in the distribution.
     *
     * @return float The value at the 95th percentile in the distribution
     */
    public function get95thPercentile()
    {
        return $this->getValue(self::P95_QUANTILE);
    }

    /**
     * Returns the value at the 98th percentile in the distribution.
     *
     * @return float The value at the 98th percentile in the distribution
     */
    public function get98thPercentile()
    {
        return $this->getValue(self::P98_QUANTILE);
    }

    /**
     * Returns the value at the 99th percentile in the distribution.
     *
     * @return float The value at the 99th percentile in the distribution
     */
    public function get99thPercentile()
    {
        return $this->getValue(self::P99_QUANTILE);
    }

    /**
     * Returns the value at the 99.9th percentile in the distribution.
     *
     * @return float The value at the 99.9th percentile in the distribution
     */
    public function get999thPercentile()
    {
        return $this->getValue(self::P999_QUANTILE);
    }

    /**
     * Returns the entire set of values in the snapshot.
     *
     * @return array The entire set of values in the snapshot
     */
    public function getValues()
    {
        return $this->values;
    }

    /**
     * Writes the values of the sample to the given file.
     *
     * @param string $filename Output the file to which the values will be written
     *
     * @throws \RuntimeException If there is an error writing the values
     */
    public function dump($filename)
    {
        $data = implode("\n", array_values($this->values));
        if (false === @file_put_contents($filename, $data)) {
            throw new \RuntimeException("Could not write to file '$filename'");
        }
    }
}