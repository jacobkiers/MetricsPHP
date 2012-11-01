<?php

namespace MetricsPHP\Stats;

/**
* An exponentially-weighted moving average.
*
* @see <a href="http://www.teamquest.com/pdfs/whitepaper/ldavg1.pdf">UNIX Load Average Part 1: How
* It Works</a>
* @see <a href="http://www.teamquest.com/pdfs/whitepaper/ldavg2.pdf">UNIX Load Average Part 2: Not
* Your Average Average</a>
 * 
*/
class EWMA
{
    private $initialized = false;

    private $rate = 0.0;

    private $uncounted = 0;

    private $alpha;

    private $interval;

    /**
     * Returns the alpha decay value for the EWMA.
     *
     * @param integer $interval_in_seconds The interval with which should be ticked.
     * @param integer $time_in_seconds The time over which the average should be calculated.
     *
     * @return double
     */
    private static function getAlphaValue($interval_in_seconds, $time_in_seconds)
    {
        return 1-exp(-$interval_in_seconds/$time_in_seconds);
    }

    /**
     * Creates a new EWMA which is equivalent to the UNIX one minute load average and which expects
     * to be ticked every 5 seconds.
     *
     * @return a one-minute EWMA
     */
    public static function oneMinuteEWMA()
    {
        return new EWMA(60, 5);
    }

    /**
     * Creates a new EWMA which is equivalent to the UNIX five minute load average and which expects
     * to be ticked every 5 seconds.
     *
     * @return a five-minute EWMA
     */
    public static function fiveMinuteEWMA()
    {
        return new EWMA(300, 5);
    }

    /**
     * Creates a new EWMA which is equivalent to the UNIX fifteen minute load average and which
     * expects to be ticked every 5 seconds.
     *
     * @return a fifteen-minute EWMA
     */
    public static function fifteenMinuteEWMA()
    {
        return new EWMA(900, 5);
    }

    /**
     * Create a new EWMA with a specific smoothing constant.
     *
     * For example, to create a UNIX-like 5-minute load average, use:
     * <code>
     * $time = 300;
     * $interval = 5;
     * $ewma = new EWMA($time, $interval);
     * </code>
     *
     * @param integer $time Create the load average over the given time (in seconds).
     * @param integer $interval The expected tick interval (in seconds).
     */
    public function __construct($time, $interval)
    {
        $this->alpha = self::getAlphaValue($interval, $time);

        /* Not sure why the interval has to be converted to nanoseconds. */
        $this->interval = $interval * 1000*1000*1000;
    }

    /**
     * Update the moving average with a new value.
     *
     * @param n the new value
     */
    public function update($n)
    {
        $this->uncounted += $n;
    }

    /**
     * Mark the passage of time and decay the current rate accordingly.
     */
    public function tick()
    {
        $count = $this->uncounted;
        $this->uncounted = 0;

        $instantRate = $count / $this->interval;

        if ($this->initialized) {
            $this->rate += ($this->alpha * ($instantRate - $this->rate));
        } else {
            $this->rate = $instantRate;
            $this->initialized = true;
        }
    }

    /**
     * Returns the rate in the given units of time.
     *
     * @return integer The rate
     */
    public function getRate()
    {
        /* In seconds, the billion here is nanoseconds. Not sure why it is needed.*/
        return $this->rate * 1000*1000*1000;
    }
}