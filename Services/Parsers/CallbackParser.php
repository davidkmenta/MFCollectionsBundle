<?php

namespace MFCollectionsBundle\Services\Parsers;

class CallbackParser
{
    const FUNCTION_REGEX = '#^\(([A-z0-9, \$]*?){1}\)[ ]?\=\>[ ]?(.{1,})$#u';
    const PARAM_REGEX = '#^\$[A-z0-9\_]{1,}$#';
    const ARGUMENT_SEPARATOR = ',';
    const ARRAY_FUNCTION_OPERATOR = '=>';

    /** @var bool */
    private $debug = true;

    /**
     * @param bool $debug
     */
    public function setDebug($debug)
    {
        $this->debug = $debug;
    }

    /**
     * @param string $func
     * @return callable
     */
    public function parseArrayFunc($func)
    {
        if (is_callable($func)) {
            return $func;
        }

        $this->assertString($func);

        $func = trim($func);
        $this->assertSyntax($func);

        $parts = explode(self::ARRAY_FUNCTION_OPERATOR, $func, 2);  // ['($a, $b)', '$a + $b']
        $params = explode(self::ARGUMENT_SEPARATOR, str_replace(['(', ')', ' '], '', $parts[0]));   // ['$a', '$b']

        $this->assertParamsSytax($params);

        $functionBody = trim(trim($parts[1], '; {}'), '; ');  // '$a + $b'

        if (strpos($functionBody, 'return') === false) {
            $functionString = sprintf('$callback = function(%s){return %s;};', implode(',', $params), $functionBody);
        } else {
            $functionString = sprintf('$callback = function(%s){%s;};', implode(',', $params), $functionBody);
        }
        eval($functionString);

        $this->assertCallable($callback);

        return $callback;
    }

    /**
     * @param string $string
     */
    private function assertString($string)
    {
        if ($this->debug && !is_string($string) || empty($string)) {
            throw new \InvalidArgumentException('Array function has to be string');
        }
    }

    /**
     * @param string $string
     */
    private function assertSyntax($string)
    {
        if ($this->debug && !preg_match(self::FUNCTION_REGEX, $string)) {
            throw new \InvalidArgumentException('Array function is not in right format');
        }
    }

    /**
     * @param array $params
     */
    private function assertParamsSytax(array $params)
    {
        if (!$this->debug) {
            return;
        }

        foreach ($params as $param) {
            if (!empty($param) && !preg_match(self::PARAM_REGEX, $param)) {
                throw new \InvalidArgumentException('Params are not in right format');
            }
        }
    }

    /**
     * @param string $callback
     */
    private function assertCallable($callback)
    {
        if ($this->debug && !is_callable($callback)) {
            throw new \InvalidArgumentException('Given string is not in right format');
        }
    }
}
