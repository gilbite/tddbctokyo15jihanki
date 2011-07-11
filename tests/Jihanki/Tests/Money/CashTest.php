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
            $money = new Cash($money);
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
        $money = new Cash($money);
    }

    public function provideNonAvailable()
    {
        return array(
            array('hoge'),
            array(-100),
            array(120),
        );
    }

    /**
     * testIsCoin 
     * 
     * @dataProvider provideIsCoin
     */
    public function testIsCoin($money, $expected)
    {
        $cash = new Cash($money);
        $this->assertSame($expected, $cash->isCoin());
    }

    public function provideIsCoin()
    {
        return array(
            array(1, true),
            array(5, true),
            array(10, true),
            array(50, true),
            array(100, true),
            array(500, true),
            array(1000, false),
            array(2000, false),
            array(5000, false),
            array(10000, false),
        );
    }


    
}
