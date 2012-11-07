<?php

namespace MetricsPHP\Helpers;

class MinKeyQueue
{
    protected $values = array();

    public function add($key, $value)
    {
        $key = (string)$key;
        $this->values[$key] = $value;
    }

    public function contains($key)
    {
        $key = (string)$key;
        return isset($this->values[$key]);
    }

    public function addIfAbsent($key, $value)
    {
        $key = (string)$key;
        if (!$this->contains($key)) {
            $this->add($key, $value);
            return null;
        }

        return $this->get($key);
    }

    public function get($key)
    {
        $key = (string)$key;
        if ($this->contains($key)) {
            return $this->values[$key];
        }
        return null;
    }

    public function sortValues()
    {
        uksort($this->values, function ($key1, $key2) {
                $key1 = (float)$key1;
                $key2 = (float)$key2;

                if ($key1 == $key2)
                    return 0;
                return ($key1 < $key2) ? -1 : 1;
            });
    }

    public function getKeys()
    {
        $this->sortValues();
        return array_keys($this->values);
    }

    public function remove($key)
    {
        $key = (string)$key;
        if (!$this->contains($key)) {
            return null;
        }

        $old_value = $this->get($key);
        unset($this->values[$key]);

        return $old_value;
    }

    public function getFirstKey()
    {
        $keys = $this->getKeys();

        if (isset($keys[0])) {
            return $keys[0];
        }

        return null;
    }

    public function count()
    {
        return count($this->values);
    }

    /**
     * Returns an array with all the values.
     *
     * @return array
     */
    public function toArray()
    {
        $this->sortValues();
        return $this->values;
    }
}
