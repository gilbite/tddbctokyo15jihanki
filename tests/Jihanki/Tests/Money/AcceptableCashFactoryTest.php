<?php

namespace Gilbite\Jihanki\Tests\Money;

use Gilbite\Jihanki\Money\Cash;
use Gilbite\Jihanki\Money\AcceptableCashFactory;
use Gilbite\Jihanki\Money\UnacceptableCashException;

class AcceptableCashFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * testFactory 
     * 
     * @dataProvider provideConditions
     */
    public function testFactory($money, $shouldBeException)
    {
        $throwed = false;
        try {
            $cash = AcceptableCashFactory::factory($money);
            $this->assertInstanceOf('Gilbite\\Jihanki\\Money\\Cash', $cash);
        } catch (UnacceptableCashException $e){
            $throwed = true;
        }

        $this->assertSame($shouldBeException, $throwed);
    }

    public function provideConditions()
    {
        return array(
            array(10, false),
            array(50, false),
            array(100, false),
            array(500, false),
            array(1000, false),
            array(1, true),
            array(5, true),
            array(2000, true),
            array(5000, true),
            array(10000, true),
        );
    }
}
