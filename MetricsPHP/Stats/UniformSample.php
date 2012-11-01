<?php

namespace MetricsPHP\Stats;

use \MetricsPHP\Core\Counter;

class UniformSample implements Sample
{
    const BITS_PER_LONG = 63;

    private $count;

    private $maxSize = 0;

    private $values;

    /**
     * Creates a new {@link UniformSample}.
     *
     * @param reservoirSize the number of samples to keep in the sampling reservoir
     */
    public function __construct($reservoirSize)
    {
        $this->maxSize = $reservoirSize;
        $this->count = new Counter();
        $this->values = new \SplFixedArray($this->maxSize);
    }

    public function clear()
    {
        $this->values = new \SplFixedArray($this->maxSize);
        $this->count->clear();
    }

    public function size()
    {
        if ($this->count->getCount() > $this->maxSize) {
            return $this->maxSize;
        }
        return $this->count->getCount();
    }

    public function update($value)
    {
        $c = $this->count->inc();
        if ($c <= $this->maxSize) {
            $this->values[$c - 1] = $value;
        } else {
            $r = self::nextLong($c);
            if ($r < $this->maxSize) {
                $this->values[$r] = $value;
            }
        }
    }

    /**
     * Get a pseudo-random long uniformly between 0 and n-1.
     *
     * @param int $n The bound
     * @return float A value select randomly from the range {@code [0..n)}.
     */
    private static function nextLong($n)
    {
        $bits = $val = 0;
        do {
            $bits = mt_rand() & (~(1 << self::BITS_PER_LONG));
            $val = $bits % $n;
        } while ($bits - $val + ($n - 1) < 0);
        return $val;
    }

    /**
     * Returns a snapshot of these values.
     *
     * @return \MetricsPHP\Stats\Snapshot
     */
    public function getSnapshot()
    {
        return new Snapshot($this->values);
    }
}