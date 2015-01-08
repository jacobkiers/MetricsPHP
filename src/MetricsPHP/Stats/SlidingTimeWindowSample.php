<?php

namespace Supernova\Metric\Stats;

use Supernova\Metric\Core\Clock;
use \MetricsPHP\Helpers\MinKeyQueue;

class SlidingTimeWindowSample implements Sample{
    const COLLISION_BUFFER = 256;
    const TRIM_THRESHOLD = 256;

    protected $clock;
    protected $measurements;
    protected $window;
    protected $lastTick;
    protected $count;

    public function __construct($window = 60, Clock $clock = null)
    {
        if (null === $clock) {
            $clock = new Clock();
        }

        $this->setClock($clock);
        $this->window = 1000000000 * $window * static::COLLISION_BUFFER;
        $this->lastTick = $this->clock->getTick() * static::COLLISION_BUFFER;
        $this->count = 0;
    }

    public function setClock(Clock $clock)
    {
        $this->clock = $clock;
        $this->clear();
    }

    public function clear()
    {
        $this->count = 0;
        $this->measurements = new MinKeyQueue();
        $this->lastTick = $this->clock->getTick() * static::COLLISION_BUFFER;
    }

    public function update($value){
        $this->count++;
        if($this->count % static::TRIM_THRESHOLD == 0){
            $this->trim();
        }
        $this->measurements->add($this->getTick(), $value);
    }

    public function getSnapshot()
    {
        $this->trim();
        return new Snapshot(array_values($this->measurements->toArray()));
    }

    public function size()
    {
        $this->trim();
        return $this->measurements->count();
    }

    private function getTick(){
        for(;;){
            $oldTick = $this->lastTick;
            $tick = $this->clock->getTick() * static::COLLISION_BUFFER;
            // ensure the tick is strictly incrementing even if there are duplicate ticks
            $newTick = $tick - $oldTick > 0 ? $tick : $oldTick + 1;
            if($this->lastTick == $oldTick ){
                $this->lastTick = $oldTick;
                return $newTick;
            }
        }
    }

    public function trim(){
        $expire = $this->getTick() - $this->window;
        $arr = $this->measurements->toArray();
        foreach ($arr as $key => $value) {
            if($key < $expire){
                $this->measurements->remove[$key];
            }
        }
    }
}

?>