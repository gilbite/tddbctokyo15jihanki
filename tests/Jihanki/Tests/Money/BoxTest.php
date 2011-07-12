<?php

namespace Gilbite\Jihanki\Tests\Money;

use Gilbite\Jihanki\Money\Cash;
use Gilbite\Jihanki\Money\AcceptableCashFactory as CashFactory;
use Gilbite\Jihanki\Money\Box;

class BoxTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->box = new Box();
    }

    public function testAdd()
    {
        $this->box->add(CashFactory::factory(10), 1);
        $this->assertEquals(10, $this->box->getAmount());
        $this->box->add(CashFactory::factory(100));
        $this->assertEquals(110, $this->box->getAmount());
    }

    /**
     * @dataProvider provideChangeCondition
     */
    public function testIsChangeAvailable($set, $change, $isAvailable)
    {
        foreach ($set as $money => $amount) {
            $this->box->add(CashFactory::factory($money), $amount);
        }
        $this->assertSame($isAvailable, $this->box->isChangeAvailable($change));
    }

    public function provideChangeCondition()
    {
        return array(
            array(
                array(10 => 2, 100 => 1),
                120,
                true
            ),
            array(
                array(10 => 2, 100 => 1),
                90,
                false
            ),
            array(
                array(10 => 12, 100 => 1),
                90,
                true
            ),
            array(
                array(10 => 1, 500 => 1),
                380,
                false
            ),
            array(
                array(10 => 38, 500 => 1),
                880,
                true
            ),
        );
    }

    public function testPayoutChange()
    {

        // initial
        $this->box->add(CashFactory::factory(10),  10);
        $this->box->add(CashFactory::factory(50),  10);
        $this->box->add(CashFactory::factory(100), 10);
        $this->box->add(CashFactory::factory(500), 10);

        // accept
        $this->box->add(CashFactory::factory(1000), 1);

        $this->assertEquals(7600, $this->box->getAmount());

        $expected = new \SplObjectStorage();
        $expected[CashFactory::factory(500)] = 1;
        $expected[CashFactory::factory(100)] = 3;
        $expected[CashFactory::factory(50)]  = 1;
        $expected[CashFactory::factory(10)]  = 3;

        $this->assertEquals($expected, $this->box->payoutChange(880));


        $expected = new \SplObjectStorage();
        $expected[CashFactory::factory(1000)]= 1;
        $expected[CashFactory::factory(500)] = 9;
        $expected[CashFactory::factory(100)] = 7;
        $expected[CashFactory::factory(50)]  = 9;
        $expected[CashFactory::factory(10)]  = 7;

        $this->assertEquals($expected, $this->box->getBox());
        $this->assertEquals(6720, $this->box->getAmount());
    }
}
