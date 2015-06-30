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
        $this->assertInstanceOf(\IteratorAggregate::class, $this->list);
        $this->assertInstanceOf(\Countable::class, $this->list);
    }

    /**
     * @param array $array
     * @param bool $recursive
     *
     * @dataProvider arrayProvider
     */
    public function testShouldCreateMapFromArray(array $array, $recursive)
    {
        $list = ListCollection::createFromArray($array, $recursive);

        $this->assertEquals($array, $list->toArray());
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
                'array' => [1, 'value', 3, ['val', 4], [[5, 6]]],
                'recursive' => true,
            ],
            [
                'array' => [1, 'value', 3, ['val', 4], [[5, 6]]],
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
        $subArray = ['value'];

        $array = [
            1,
            $subArray,
        ];

        $list = ListCollection::createFromArray($array, $recursive);

        if ($recursive) {
            $this->assertInstanceOf(ListCollection::class, $list->last());
        } else {
            $this->assertEquals($subArray, $list->last());
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
     * @param mixed $value
     *
     * @dataProvider addItemsProvider
     */
    public function testShouldAddItemsToMap($value)
    {
        $this->list->add($value);

        $this->assertEquals($value, $this->list->pop());
    }

    public function addItemsProvider()
    {
        return [
            ['value' => 'string-value'],
            ['value' => 2],
            ['value' => 42],
            ['value' => false],
            ['value' => 24.12],
        ];
    }

    public function testShouldIterateThroughMap()
    {
        $list = ListCollection::createFromArray(['one', 'two', 3]);

        $i = 0;
        foreach ($list as $value) {
            if ($i === 0) {
                $this->assertEquals('one', $value);
            } elseif ($i === 1) {
                $this->assertEquals('two', $value);
            } elseif ($i === 2) {
                $this->assertEquals(3, $value);
            }
            $i++;
        }
    }

    /**
     * @param array $array
     *
     * @dataProvider arrayProvider
     */
    public function testShouldGetCount(array $array)
    {
        $originalCount = count($array);
        $list = ListCollection::createFromArray($array);

        $this->assertCount($originalCount, $list);

        $list->add('value');
        $this->assertCount($originalCount + 1, $list);
    }

    public function testShouldHasValue()
    {
        $valueExists = 'has-value';
        $valueDoesntExist = 'has-no-value';

        $this->list->add($valueExists);

        $this->assertContains($valueExists, $this->list);
        $this->assertNotContains($valueDoesntExist, $this->list);

        $this->assertTrue($this->list->contains($valueExists));
        $this->assertFalse($this->list->contains($valueDoesntExist));
    }

    public function testShouldRemoveItem()
    {
        $value = 'value';

        $this->list->add($value);
        $this->assertTrue($this->list->contains($value));

        $this->list->removeFirst($value);
        $this->assertFalse($this->list->contains($value));
    }
}
