<?php

namespace spec\MetricsPHP\Core;

use PHPSpec2\ObjectBehavior;

class Gauge extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('woo');    
    }

    function it_returns_a_value()
    {
        $this->getValue()->shouldBe('woo');
    }
}
