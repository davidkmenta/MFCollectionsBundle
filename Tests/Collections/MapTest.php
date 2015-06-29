<?php

namespace MFCollectionsBundle\Tests\Collections;

use MFCollectionsBundle\Collections\Map;

class MapTest extends \PHPUnit_Framework_TestCase
{
    /** @var Map */
    private $map;

    public function setUp()
    {
        $this->map = new Map();
    }

    public function testShouldImplementsInterfaces()
    {
        $this->assertInstanceOf(\ArrayAccess::class, $this->map);
        $this->assertInstanceOf(\IteratorAggregate::class, $this->map);
        $this->assertInstanceOf(\Countable::class, $this->map);
    }

    /**
     * @param array $array
     * @param bool $recursive
     *
     * @dataProvider arrayProvider
     */
    public function testShouldCreateMapFromArray(array $array, $recursive)
    {
        $map = Map::createFromArray($array, $recursive);

        $this->assertEquals($array, $map->toArray());
    }

    public function arrayProvider()
    {
        return [
            [
                'array' => [],
                'recursive' => false,
            ],
            [
                'array' => [1, 2, 3],
                'recursive' => false,
            ],
            [
                'array' => [1, 'value', 3],
                'recursive' => true,
            ],
            [
                'array' => [1, 'value', 3, ['val', 4], ['array' => [5, 6]]],
                'recursive' => true,
            ],
            [
                'array' => [1, 'value', 3, ['val', 4], ['array' => [5, 6]]],
                'recursive' => false,
            ],
        ];
    }

    /**
     * @param bool $recursive
     *
     * @dataProvider recursiveProvider
     */
    public function testShouldCreateMapFromArrayWithSubArray($recursive)
    {
        $key = 'array-key';
        $subArray = ['key' => 'value'];

        $array = [
            'key' => 1,
            $key => $subArray,
        ];

        $map = Map::createFromArray($array, $recursive);

        if ($recursive) {
            $this->assertInstanceOf(Map::class, $map[$key]);
        } else {
            $this->assertEquals($subArray, $map[$key]);
        }
    }

    public function recursiveProvider()
    {
        return [
            ['recursive' => false],
            ['recursive' => true],
        ];
    }

    /**
     * @param mixed $key
     * @param mixed $value
     *
     * @dataProvider addItemsProvider
     */
    public function testShouldAddItemsToMapArrayWay($key, $value)
    {
        $this->map[$key] = $value;

        $this->assertEquals($value, $this->map[$key]);
    }

    /**
     * @param mixed $key
     * @param mixed $value
     *
     * @dataProvider addItemsProvider
     */
    public function testShouldAddItemsToMap($key, $value)
    {
        $this->map->set($key, $value);

        $this->assertEquals($value, $this->map->get($key));
    }

    public function addItemsProvider()
    {
        return [
            [
                'key' => 'string-key',
                'value' => 'string-value',
            ],
            [
                'key' => 1,
                'value' => 2,
            ],
            [
                'key' => '5',
                'value' => 42,
            ],
            [
                'key' => true,
                'value' => false,
            ],
            [
                'key' => 24.23,
                'value' => 24.12,
            ],
        ];
    }

    /**
     * @param object|array $key
     *
     * @dataProvider invalidKeyProvider
     *
     * @expectedException \InvalidArgumentException
     */
    public function testShouldThrowInvalidArgumentExceptionOnAddingObjectArrayWay($key)
    {
        $this->map->set($key, 'value');
    }

    /**
     * @param object|array $key
     *
     * @dataProvider invalidKeyProvider
     *
     * @expectedException \InvalidArgumentException
     */
    public function testShouldThrowInvalidArgumentExceptionOnAddingObject($key)
    {
        $this->map[$key] = 'value';
    }

    public function invalidKeyProvider()
    {
        return [
            ['key' => new \stdClass()],
            ['key' => []],
        ];
    }

    public function testShouldIterateThroughMap()
    {
        $map = Map::createFromArray([1 => 'one', 2 => 'two', 'three' => 3]);

        $i = 0;
        foreach ($map as $key => $value) {
            if ($i === 0) {
                $this->assertEquals(1, $key);
                $this->assertEquals('one', $value);
            } elseif ($i === 1) {
                $this->assertEquals(2, $key);
                $this->assertEquals('two', $value);
            } elseif ($i === 2) {
                $this->assertEquals('three', $key);
                $this->assertEquals(3, $value);
            }
            $i++;
        }
    }
}
