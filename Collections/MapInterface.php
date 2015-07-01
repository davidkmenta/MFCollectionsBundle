<?php

namespace MFCollectionsBundle\Collections;

interface MapInterface extends CollectionInterface, \ArrayAccess, \IteratorAggregate, \Countable
{
    /**
     * @param mixed $key
     * @return bool
     */
    public function containsKey($key);

    /**
     * @param mixed $value
     * @return bool
     */
    public function contains($value);

    /**
     * @param mixed $value
     * @return mixed|false
     */
    public function find($value);

    /**
     * @param mixed $key
     * @return mixed
     */
    public function get($key);

    /**
     * @param mixed $key
     * @param mixed $value
     */
    public function set($key, $value);

    /** @param mixed $key */
    public function remove($key);

    /**
     * @param callable $callback
     * @return MapInterface
     */
    public function each($callback);

    /**
     * @param callable $callback
     * @return MapInterface
     */
    public function map($callback);

    /**
     * @param callable $callback
     * @return MapInterface
     */
    public function filter($callback);

    /** @return ListInterface */
    public function keys();

    /** @return ListInterface */
    public function values();
}
