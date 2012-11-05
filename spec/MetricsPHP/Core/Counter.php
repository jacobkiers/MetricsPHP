<?php

namespace spec\MetricsPHP\Core;

use PHPSpec2\ObjectBehavior;

class Counter extends ObjectBehavior
{
    function it_should_be_initializable()
    {
        $this->shouldHaveType('MetricsPHP\Core\Counter');
    }

    function it_starts_with_zero()
    {
        $this->getCount()->shouldBe(0);
    }

    function it_increments_by_one()
    {
        $this->inc();
        $this->getCount()->shouldBe(1);
    }

    function it_increments_by_an_arbitrary_delta()
    {
        $this->inc(12);
        $this->getCount()->shouldBe(12);
    }

    function it_decrements_by_one()
    {
        $this->dec();
        $this->getCount()->shouldBe(-1);
    }

    function it_decrements_by_an_arbitrary_delta()
    {
        $this->dec(12);
        $this->getCount()->shouldBe(-12);
    }

    function it_is_zero_after_being_cleared()
    {
        $this->inc(3);
        $this->clear();
        $this->getCount()->shouldBe(0);
    }
}
