<?php

namespace MFCollectionsBundle\Collections\Enhanced;

use MFCollectionsBundle\Services\Parsers\CallbackParser;

class Map extends \MFCollectionsBundle\Collections\Map
{
    /** @var CallbackParser */
    private $callbackParser;

    public function __construct()
    {
        parent::__construct();
        $this->callbackParser = new CallbackParser();
    }

    /**
     * @param callable (key:mixed, value:mixed):mixed $callback
     * @return static
     */
    public function map($callback)
    {
        $callback = $this->callbackParser->parseArrayFunc($callback);
        return parent::map($callback);
    }

    /**
     * @param callable (key:mixed, value:mixed):bool $callback
     * @return static
     */
    public function filter($callback)
    {
        $callback = $this->callbackParser->parseArrayFunc($callback);
        return parent::filter($callback);
    }
}
