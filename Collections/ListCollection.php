<?php

namespace MFCollectionsBundle\Collections;

class ListCollection implements CollectionInterface, \IteratorAggregate, \Countable
{
    /** @var array */
    private $listArray;

    public function __construct()
    {
        $this->listArray = [];
    }

    /**
     * @param array $array
     * @param bool $recursive
     * @return ListCollection
     */
    public static function createFromArray(array $array, $recursive = false)
    {
        $map = new self();

        foreach ($array as $key => $value) {
            if ($recursive && is_array($value)) {
                $map->add(self::createFromArray($value, true));
            } else {
                $map->add($value);
            }
        }

        return $map;
    }

    /** @return array */
    public function toArray()
    {
        $array = [];
        foreach ($this->listArray as $value) {
            if ($value instanceof CollectionInterface) {
                $value = $value->toArray();
            }

            $array[] = $value;
        }

        return $array;
    }

    /**
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->listArray);
    }

    /**
     * @param mixed $value
     */
    public function add($value)
    {
        $this->listArray[] = $value;
    }

    /**
     * @param mixed $value
     */
    public function unshift($value)
    {
        array_unshift($this->listArray, $value);
    }

    /**
     * @return mixed
     */
    public function pop()
    {
        return array_pop($this->listArray);
    }

    /**
     * @return mixed
     */
    public function shift()
    {
        return array_shift($this->listArray);
    }

    /**
     * @return mixed
     */
    public function first()
    {
        $list = $this->listArray;

        return array_shift($list);
    }

    public function last()
    {
        $list = $this->listArray;

        return array_pop($list);
    }

    /**
     * @return ListCollection
     */
    public function sort()
    {
        $sortedMap = $this->listArray;
        sort($sortedMap);

        return self::createFromArray($sortedMap);
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->listArray);
    }

    /**
     * @param mixed $value
     * @return bool
     */
    public function contains($value)
    {
        return $this->find($value) !== false;
    }

    /**
     * @param mixed $value
     * @return int|false
     */
    private function find($value)
    {
        return array_search($value, $this->listArray, true);
    }

    /**
     * @param mixed $value
     */
    public function removeFirst($value)
    {
        $index = $this->find($value);

        if ($index !== false) {
            unset($this->listArray[$index]);
        }
    }

    /**
     * @param mixed $value
     */
    public function removeAll($value)
    {
        throw new \Exception('Not implemented yet');
    }
}
