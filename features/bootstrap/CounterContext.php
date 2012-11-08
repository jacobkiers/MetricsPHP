<?php

use Behat\Behat\Context\ClosuredContextInterface,
    Behat\Behat\Context\TranslatedContextInterface,
    Behat\Behat\Context\BehatContext,
    Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode,
    Behat\Gherkin\Node\TableNode;

use MetricsPHP\Core\Counter;

class CounterContext extends BehatContext
{
    /**
     * @Given /^a counter$/
     */
    public function aCounter()
    {
        $this->counter = new Counter();
    }

    /**
     * @Given /^it is incremented by (\d+)$/
     */
    public function itIsIncrementedBy($arg1)
    {
        $this->counter->inc($arg1);
    }

    /**
     * @Then /^the count is (-?\d+)$/
     */
    public function theCountIs($arg1)
    {
        assertEquals($arg1, $this->counter->getCount());
    }

    /**
     * @Given /^it is decremented by (\d+)$/
     */
    public function itIsDecrementedBy($arg1)
    {
        $this->counter->dec($arg1);
    }

    /**
     * @Given /^it is cleared$/
     */
    public function itIsCleared()
    {
        $this->counter->clear();
    }
}
