<?php

namespace Gilbite\Jihanki\Money;

class AcceptableCashFactory
{
    private function __construct($money)
    {
    }

    public function __clone()
    {
        throw new \Exception('cannot clone');
    }

    /**
     * factory 
     * 
     * @param int $money 
     * @static
     * @access public
     * @return Gilbite\Jihanki\Money\Cash
     */
    public static function factory($money)
    {
        if (!in_array($money, self::getAcceptableCash(), true)) {
            throw new UnacceptableCashException();
        }

        return Cash::getInstance($money);
    }

    /**
     * getAcceptableCash 
     * 
     * @static
     * @access public
     * @return array list of int
     */
    public static function getAcceptableCash()
    {
        return array(10, 50, 100, 500, 1000);
    }


    
}
