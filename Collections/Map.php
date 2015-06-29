<?php

namespace MFCollectionsBundle\Collections;

class Map implements CollectionInterface, \ArrayAccess, \IteratorAggregate, \Countable
{
    /** @var array */
    private $mapArray;

    public function __construct()
    {
        $this->mapArray = [];
    }

    /**
     * @param array $array
     * @param bool $recursive
     * @return Map
     */
    public static function createFromArray(array $array, $recursive = false)
    {
        $map = new self();

        foreach ($array as $key => $value) {
            if ($recursive && is_array($value)) {
                $map->set($key, self::createFromArray($value, true));
            } else {
                $map->set($key, $value);
            }
        }

        return $map;
    }

    /**
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->mapArray);
    }

    /**
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return $this->contains($offset);
    }

    /**
     * @param mixed $key
     * @return bool
     */
    public function contains($key)
    {
        return array_key_exists($key, $this->mapArray);
    }

    /**
     * @param mixed $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    /**
     * @param mixed $key
     * @return mixed
     */
    public function get($key)
    {
        return $this->mapArray[$key];
    }

    /**
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value)
    {
        $this->set($offset, $value);
    }

    /**
     * @param mixed $key
     * @param mixed $value
     */
    public function set($key, $value)
    {
        if (is_object($key)) {
            throw new \InvalidArgumentException('Key cannot be an Object');
        }
        if (is_array($key)) {
            throw new \InvalidArgumentException('Key cannot be an Array');
        }

        $this->mapArray[$key] = $value;
    }

    /**
     * @param mixed $offset
     */
    public function offsetUnset($offset)
    {
        $this->remove($offset);
    }

    /**
     * @param mixed $key
     */
    public function remove($key)
    {
        unset($this->mapArray[$key]);
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->mapArray);
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $array = [];
        foreach ($this->mapArray as $key => $value) {
            if ($value instanceof CollectionInterface) {
                $value = $value->toArray();
            }

            $array[$key] = $value;
        }

        return $array;
    }

    /**
     * @param callable(key:mixed, value:mixed):void $callback
     */
    public function each($callback)
    {
        $this->assertCallback($callback);

        foreach ($this->mapArray as $key => $value) {
            $callback($key, $value);
        }
    }

    /**
     * @param callable $callback
     */
    private function assertCallback($callback)
    {
        if (!is_callable($callback)) {
            throw new \InvalidArgumentException('Callback must be callable');
        }
    }
}
