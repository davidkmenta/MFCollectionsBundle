<?php

namespace MFCollectionsBundle\Collections;

class Map implements CollectionInterface, \ArrayAccess, \IteratorAggregate, \Countable
{
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
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Whether a offset exists
     * @link http://php.net/manual/en/arrayaccess.offsetexists.php
     * @param mixed $offset <p>
     * An offset to check for.
     * </p>
     * @return boolean true on success or false on failure.
     * </p>
     * <p>
     * The return value will be casted to boolean if non-boolean was returned.
     */
    public function offsetExists($offset)
    {
        // TODO: Implement offsetExists() method.
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to retrieve
     * @link http://php.net/manual/en/arrayaccess.offsetget.php
     * @param mixed $offset <p>
     * The offset to retrieve.
     * </p>
     * @return mixed Can return all value types.
     */
    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to set
     * @link http://php.net/manual/en/arrayaccess.offsetset.php
     * @param mixed $offset <p>
     * The offset to assign the value to.
     * </p>
     * @param mixed $value <p>
     * The value to set.
     * </p>
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        $this->set($offset, $value);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to unset
     * @link http://php.net/manual/en/arrayaccess.offsetunset.php
     * @param mixed $offset <p>
     * The offset to unset.
     * </p>
     * @return void
     */
    public function offsetUnset($offset)
    {
        // TODO: Implement offsetUnset() method.
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->map);
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

    public function get($key)
    {
        return $this->map[$key];
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
