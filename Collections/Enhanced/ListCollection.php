<?php

namespace MFCollectionsBundle\Collections\Enhanced;

use MFCollectionsBundle\Services\Parsers\CallbackParser;

class ListCollection extends \MFCollectionsBundle\Collections\ListCollection
{
    /** @var CallbackParser */
    private $callbackParser;

    public function __construct()
    {
        parent::__construct();
        $this->callbackParser = new CallbackParser();
    }

    /**
     * @param callable(value:mixed,index:int):mixed $callback
     * @return static
     */
    public function map($callback)
    {
        $callback = $this->callbackParser->parseArrowFunction($callback);
        return parent::map($callback);
    }

    /**
     * @param callable(value:mixed,index:int):bool $callback
     * @return static
     */
    public function filter($callback)
    {
        $callback = $this->callbackParser->parseArrowFunction($callback);
        return parent::filter($callback);
    }
}
