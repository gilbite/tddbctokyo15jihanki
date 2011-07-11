<?php

namespace Gilbite\Jihanki\Money;

class Cash
{
    protected $value = 0;

    /**
     * getAvailableCoins 
     * 
     * @static
     * @access public
     * @return array list of int
     */
    public static function getAvailableCoins()
    {
        return array(1, 5, 10, 50, 100, 500);
    }

    /**
     * getAvailableLettuces 
     * 
     * @static
     * @access public
     * @return array list of int
     */
    public static function getAvailableLettuces()
    {
        return array(1000, 2000, 5000, 10000);
    }

    /**
     * constructor
     *
     * @param int $money 
     * @throws InvalidCashException
     */
    public function __construct($money)
    {
        if (!in_array($money, array_merge( self::getAvailableCoins(), self::getAvailableLettuces() ), true)) {
            throw new InvalidCashException();
        }
        $this->value = $money;
    }

    /**
     * isCoin 
     * 
     * @access public
     * @return boolean
     */
    public function isCoin()
    {
        return in_array($this->value, self::getAvailableCoins(), true);
    }

    /**
     * getValue 
     * 
     * @access public
     * @return int
     */
    public function getValue()
    {
        return $this->value;
    }
}
