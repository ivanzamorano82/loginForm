<?php

namespace App\Util;


/**
 * Represents a set for key/value pairs of parameters.
 */
class Params
{
    /**
     * Contains parameters represented by this set.
     *
     * @var array
     */
    protected $params;


    /**
     * Creates new parameters set from given array.
     *
     * @param array $params  Array of parameters to create set of.
     */
    public function __construct($params)
    {
        $this->params = $params;
    }

    /**
     * Returns all parameters as key/value array.
     *
     * @return array
     */
    public function getAll()
    {
        return $this->params;
    }

    /**
     * Checks if specified parameter exists.
     *
     * @param string|int $key  Name of parameter.
     *
     * @return bool     Yes or no.
     */
    public function exists($key)
    {
        return isset($this->params[$key]);
    }

    /**
     * Returns space-truncated string representation of required parameter.
     *
     * @param string|int $key  Name of parameter.
     *
     * @return string   Space-truncated string representation of parameter.
     */
    public function String($key)
    {
        return self::asString($this->params[$key]);
    }

    /**
     * Returns space-truncated string representation of required parameter
     * in lower case.
     *
     * @param string|int $key  Name of parameter.
     *
     * @return string   Space-truncated string representation of parameter
     *                  in lower case.
     */
    public function StringInLowerCase($key)
    {
        return strtolower(self::asString($this->params[$key]));
    }

    /**
     * Returns integer representation of required parameter.
     *
     * @param string|int $key  Name of parameter.
     *
     * @return int      Integer representation of parameter.
     */
    public function Int($key)
    {
        return intval($this->params[$key]);
    }

    /**
     * Returns unsigned integer representation of required parameter.
     *
     * @param string|int $key  Name of parameter.
     *
     * @return int      Unsigned integer representation of parameter.
     */
    public function Uint($key)
    {
        return self::asUint($this->params[$key]);
    }

    /**
     * Returns float representation of required parameter.
     *
     * @param string|int $key  Name of parameter.
     *
     * @return float      Float representation of parameter.
     */
    public function Float($key)
    {
        return floatval($this->params[$key]);
    }

    /**
     * Returns boolean representation of required parameter.
     *
     * @param string|int $key  Name of parameter.
     *
     * @return bool      Boolean representation of parameter.
     */
    public function Bool($key)
    {
        return (bool)$this->params[$key];
    }

    /**
     * Returns array representation of required parameter.
     *
     * @param string|int $key  Name of parameter.
     *
     * @return array      Array representation of parameter.
     */
    public function Arr($key)
    {
        return (array)$this->params[$key];
    }

    /**
     * Returns required parameter as set of key/value parameters.
     *
     * @param string|int $key  Name of parameter.
     *
     * @return Params      Parameter value as set of key/value parameters.
     */
    public function Params($key)
    {
        return new Params($this->Arr($key));
    }

    /**
     * Represents given value as string.
     *
     * @param mixed $val  Value to represent as string.
     *
     * @return string   String representation of given value.
     */
    public static function asString($val)
    {
        return trim(strval($val));
    }

    /**
     * Represents given value as unsigned integer.
     *
     * @param mixed $val  Value to represent as integer.
     *
     * @return int      Unsigned integer representation of given value.
     */
    public static function asUint($val)
    {
        return abs(intval($val));
    }
}
