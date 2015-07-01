<?php

namespace MFCollectionsBundle\Tests\Collections\Enhanced;

use MFCollectionsBundle\Collections\Enhanced\Map;

class MapTest extends \MFCollectionsBundle\Tests\Collections\MapTest
{
    public function setUp()
    {
        $this->map = new Map();
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testShouldThrowExceptionWhenForeachItemInMapWithArrowFunction()
    {
        $this->map->each('($k, $v) => {}');
    }

    public function testShouldMapToNewMapByArrowFunction()
    {
        $map = Map::createFromArray([1 => 'one', 2 => 'two', 'three' => 3]);
        $newMap = $map->map('($k, $v) => $k . $v');

        $this->assertNotEquals($map, $newMap);
        $this->assertEquals([1 => '1one', 2 => '2two', 'three' => 'three3'], $newMap->toArray());
    }

    public function testShouldFilterItemsToNewMapByArrowFunction()
    {
        $map = Map::createFromArray([1 => 'one', 2 => 'two', 'three' => 3]);
        $newMap = $map->filter('($k, $v) => $k >= 1');

        $this->assertNotEquals($map, $newMap);
        $this->assertEquals([1 => 'one', 2 => 'two'], $newMap->toArray());
    }

    public function testShouldCombineMapAndFilterToCreateNewMap()
    {
        $map = Map::createFromArray([1 => 'one', 2 => 'two', 'three' => 3]);
        $newMap = $map
            ->filter('($k, $v) => $k >= 1')
            ->map('($k, $v) => $k . $v');

        $this->assertNotEquals($map, $newMap);
        $this->assertEquals([1 => '1one', 2 => '2two'], $newMap->toArray());
    }
}
