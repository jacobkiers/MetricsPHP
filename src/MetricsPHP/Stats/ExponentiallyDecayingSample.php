<?php

namespace MetricsPHP\Stats;

use \MetricsPHP\Core\Clock;
use \MetricsPHP\Helpers\MinKeyQueue;

class ExponentiallyDecayingSample implements Sample
{
    /*
     * One hour in nanoseconds.
     */
    const RESCALE_THRESHOLD = 3600000000000;

    /**
     *
     * @var MinKeyQueue
     */
    protected $values;

    protected $alpha;

    protected $reservoirSize;

    protected $count = 0;

    protected $startTime;

    protected $nextScaleTime = 0;

    /**
     * Contains a clock
     *
     * @var Clock
     */
    protected $clock;

    public function __construct($reservoirSize, $alpha, Clock $clock = null)
    {
        if (null === $clock) {
            $clock = new Clock();
        }

        $this->setClock($clock);
        $this->setAlpha($alpha);
        $this->setReservoirSize($reservoirSize);
    }

    public function setAlpha($alpha)
    {
        $this->alpha = $alpha;
        $this->clear();
    }

    public function setReservoirSize($reservoirSize)
    {
        $this->reservoirSize = $reservoirSize;
        $this->clear();
    }

    public function setClock(Clock $clock)
    {
        $this->clock = $clock;
        $this->clear();
    }

    public function clear()
    {
        $this->count = 0;
        $this->values = new MinKeyQueue();
        $this->startTime = $this->clock->getTimeInSeconds();
        $this->nextScaleTime = $this->clock->getTick() + static::RESCALE_THRESHOLD;
    }

    public function getSnapshot()
    {
        return new Snapshot(array_values($this->values->toArray()));
    }

    public function size()
    {
        return min($this->count, $this->values->count());
    }

    /**
     * Adds an old value with a fixed timestamp to the sample.
     *
     * @param value     the value to be added
     * @param timestamp the epoch timestamp of {@code value} in seconds
     */
    public function update($value, $timestamp = null)
    {
        if (null === $timestamp) {
            $timestamp = $this->currentTimeInSeconds();
        }
        $this->rescaleIfNeeded();

        $priority = $this->weight($timestamp - $this->startTime) / (mt_rand() / 1000000);

        $this->count++;

        if ($this->count <= $this->reservoirSize) {
            $this->values->add($priority, $value);
        } else {
            $first = (float)$this->values->getFirstKey();
            if ($first < $priority) {
                if (null === $this->values->addIfAbsent($priority, $value)) {
                    // ensure we always remove an item
                    while (null === $this->values->remove($first)) {
                        $first = $this->values->getFirstKey();
                    }
                }
            }
        }
    }

    private function rescaleIfNeeded()
    {
        $now = $this->clock->getTick();
        $next = $this->nextScaleTime;
        if ($now >= $next) {
            $this->rescale($now, $next);
        }
    }

    private function currentTimeInSeconds()
    {
        return $this->clock->getTimeInSeconds();
    }

    private function weight($timeInSeconds)
    {
        return exp($this->alpha * $timeInSeconds);
    }

    /* "A common feature of the above techniques—indeed, the key technique that
     * allows us to track the decayed weights efficiently—is that they maintain
     * counts and other quantities based on g(ti − L), and only scale by g(t − L)
     * at query time. But while g(ti −L)/g(t−L) is guaranteed to lie between zero
     * and one, the intermediate values of g(ti − L) could become very large. For
     * polynomial functions, these values should not grow too large, and should be
     * effectively represented in practice by floating point values without loss of
     * precision. For exponential functions, these values could grow quite large as
     * new values of (ti − L) become large, and potentially exceed the capacity of
     * common floating point types. However, since the values stored by the
     * algorithms are linear combinations of g values (scaled sums), they can be
     * rescaled relative to a new landmark. That is, by the analysis of exponential
     * decay in Section III-A, the choice of L does not affect the final result. We
     * can therefore multiply each value based on L by a factor of exp(−α(L′ − L)),
     * and obtain the correct value as if we had instead computed relative to a new
     * landmark L′ (and then use this new L′ at query time). This can be done with
     * a linear pass over whatever data structure is being used."
     */

    private function rescale($now, $next)
    {
        if ($this->nextScaleTime == $next) {
            $this->nextScaleTime = $now + static::RESCALE_THRESHOLD;
            $oldStartTime = $this->startTime;
            $this->startTime = $this->currentTimeInSeconds();
            $keys = $this->values->getKeys();
            foreach ($keys as $key) {
                $value = $this->values->remove($key);
                ;
                $new_key = $key * exp(-$this->alpha * ($this->startTime - $oldStartTime));
                $this->values->add($new_key, $value);
            }

            // make sure the counter is in sync with the number of stored samples.
            $this->count = $this->values->count();
            ;
        }
    }
}
