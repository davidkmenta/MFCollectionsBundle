<?php

namespace MFCollectionsBundle\Tests\Collections;

use MFCollectionsBundle\Collections\CollectionInterface;
use MFCollectionsBundle\Collections\ListCollection;

class ListTest extends \PHPUnit_Framework_TestCase
{
    /** @var ListCollection */
    private $list;

    public function setUp()
    {
        $this->list = new ListCollection();
    }

    public function testShouldImplementsInterfaces()
    {
        $this->assertInstanceOf(CollectionInterface::class, $this->list);
        $this->assertInstanceOf(\ArrayAccess::class, $this->list);
        $this->assertInstanceOf(\IteratorAggregate::class, $this->list);
        $this->assertInstanceOf(\Countable::class, $this->list);
    }
}
