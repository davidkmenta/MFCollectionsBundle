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
}