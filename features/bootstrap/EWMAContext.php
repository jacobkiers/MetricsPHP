<?php

use \Behat\Behat\Context\BehatContext,
    \MetricsPHP\Stats\EWMA;

require_once 'PHPUnit/Framework/Assert/Functions.php';

/**
 * Features context.
 */
class EWMAContext extends BehatContext
{
    /**
     * Contains an EWMA-instance
     * @var EWMA
     */
    protected $EWMA;

    /**
     * @Given /^a (\d+)-minute EWMA$/
     */
    public function aMinuteEwma($arg1)
    {
        $this->EWMA = new EWMA($arg1*60, 5);
    }

    /**
     * @Given /^receiving an update with value (\d+)$/
     */
    public function receivingAnUpdateWithValue($arg1)
    {
        $this->EWMA->update($arg1);
    }

    /**
     * @Given /^receiving a tick$/
     */
    public function receivingATick()
    {
        $this->EWMA->tick();
    }

    /**
     * @Then /^the rate is (\d+\.\d+)$/
     */
    public function theRateIs($arg1)
    {
        $rate = $this->EWMA->getRate();
        $true = ($rate-0.00000001 < $arg1 && $arg1 < $rate+0.00000001);
        assertTrue($true, "The rate is $rate, should be $arg1");
    }

    /**
     * @Given /^one minute elapses \((\d+) minutes total\)$/
     */
    public function oneMinuteElapsesMinutes($arg1)
    {
        for($i = 0; $i < 12; $i++) {
            $this->EWMA->tick();
        }
    }
}

