<?php

namespace MFCollectionsBundle\Collections;

class Map implements CollectionInterface, \ArrayAccess, \IteratorAggregate, \Countable
{
    /** @var array */
    private $map;

    public function __construct()
    {
        $this->map = [];
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
        return new \ArrayIterator($this->map);
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
        return array_key_exists($key, $this->map);
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
        return $this->map[$key];
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

        $this->map[$key] = $value;
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
        unset($this->map[$key]);
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->map);
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $array = [];
        foreach ($this->map as $key => $value) {
            if ($value instanceof CollectionInterface) {
                $value = $value->toArray();
            }

            $array[$key] = $value;
        }

        return $array;
    }
}
