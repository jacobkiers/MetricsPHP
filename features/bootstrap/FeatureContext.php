<?php

use Behat\Behat\Context\ClosuredContextInterface,
    Behat\Behat\Context\TranslatedContextInterface,
    Behat\Behat\Context\BehatContext,
    Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode,
    Behat\Gherkin\Node\TableNode;


use MetricsPHP\Core\ManualClock;
use MetricsPHP\Stats\ExponentiallyDecayingSample;

require_once 'PHPUnit/Framework/Assert/Functions.php';

/**
 * Features context.
 */
class FeatureContext extends BehatContext
{
    /**
     * Initializes context.
     * Every scenario gets it's own context object.
     *
     * @param array $parameters context parameters (set them up through behat.yml)
     */
    public function __construct(array $parameters)
    {
        // Initialize your context here
    }

    /**
     * Contains a sample
     *
     * @var Sample
     */
    protected $sample;

    protected $clock;

    /**
     * @Given /^an exponentially decaying sample with size (\d+) and alpha value (0\.\d+)$/
     */
    public function anExponentiallyDecayingSampleWithSizeAndAlphaValue($reservoirSize, $alpha)
    {
        $this->sample = new ExponentiallyDecayingSample($reservoirSize, $alpha);
    }

    /**
     * @Given /^receiving (\d+) elements$/
     */
    public function receivingElements($arg1)
    {
        for($i = 0; $i < $arg1; $i++) {
            $this->sample->update($i);
        }
    }

    /**
     * @Given /^receiving (\d+) elements? starting at (\d+)$/
     */
    public function receivingElementsStartingAt($arg1, $arg2)
    {
        for($i = 0; $i < $arg1; $i++) {
            $this->sample->update($arg2+$i);
        }
    }

    /**
     * @Given /^receiving (\d+) elements starting at (\d+) every (\d+) milliseconds$/
     */
    public function receivingElementsStartingAtEveryMilliseconds($arg1, $arg2, $arg3)
    {
        for($i = 0; $i < $arg1; $i++) {
            $this->sample->update($arg2+$i);
            $this->clock->addMillis($arg3);
        }
    }

    /**
     * @Then /^its size should be (\d+)$/
     */
    public function itsSizeShouldBe($arg1)
    {
        assertEquals($arg1, $this->sample->size());
    }

    /**
     * @Given /^its snapshot size should be (\d+)$/
     */
    public function itsSnapshotSizeShouldBe($arg1)
    {
        $snapshot = $this->sample->getSnapshot();
        assertEquals($arg1, $snapshot->size());
    }

    /**
     * @Given /^all snapshot values should be between (\d+) and (\d+)$/
     */
    public function allSnapshotValuesShouldBeBetweenAnd($arg1, $arg2)
    {
        $minValue = $arg1;
        $maxValue = $arg2;

        $snapshot = $this->sample->getSnapshot();

        foreach($snapshot->getValues() as $value) {
            if ($value < $minValue) {
                $minValue = $value;
            }
            if ($value > $maxValue) {
                $maxValue = $value;
            }
        }

        assertEquals($arg1, $minValue);
        assertEquals($arg2, $maxValue);
    }

    /**
     * @Given /^having a manual clock$/
     */
    public function havingAManualClock()
    {
        $this->clock = new ManualClock();
        $this->sample->setClock($this->clock);
    }


    /**
     * @Given /^the clock moves (\d+) hours$/
     */
    public function theClockMovesHours($arg1)
    {
        $this->clock->addHours($arg1);
    }

}
