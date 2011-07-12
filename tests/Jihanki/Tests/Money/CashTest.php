<?php

namespace Gilbite\Jihanki\Tests\Money;

use Gilbite\Jihanki\Money\Cash;
use Gilbite\Jihanki\Money\InvalidCashException;

class CashTest extends \PHPUnit_Framework_TestCase
{

    /**
     * testAvailable 
     * 
     * @dataProvider provideAvailable
     */
    public function testAvailable($money)
    {
        try {
            $money = Cash::getInstance($money);
            return '';
        } catch (InvalidCashException $e){
            $this->fail('invalid money');
        }
    }

    public function provideAvailable()
    {
        return array(
            array(1),
            array(5),
            array(10),
            array(50),
            array(100),
            array(500),
            array(1000),
            array(2000),
            array(5000),
            array(10000),
        );
    }

    /**
     * testNonAvailabe 
     *
     * @expectedException Gilbite\Jihanki\Money\InvalidCashException
     * @dataProvider provideNonAvailable
     */
    public function testNonAvailabe($money)
    {
        $money = Cash::getInstance($money);
    }

    public function provideNonAvailable()
    {
        return array(
            array('hoge'),
            array(-100),
            array(120),
        );
    }

}
