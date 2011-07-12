<?php

namespace Gilbite\Jihanki\Tests;

use Gilbite\Jihanki\Jihanki;
use Gilbite\Jihanki\Money\AcceptableCashFactory as CashFactory;
use Gilbite\Jihanki\Money\Box;
use Gilbite\Jihanki\Stock\Stock;
use Gilbite\Jihanki\Stock\Item;

class JihankiTest extends \PHPUnit_Framework_TestCase
{
    protected $jihank = null;

    public function setUp()
    {
        $this->jihanki = new Jihanki(new Stock(), new Box());
        $this->jihanki->getCashBox()->add(CashFactory::factory(10), 10);
        $this->jihanki->getCashBox()->add(CashFactory::factory(50), 10);
        $this->jihanki->getCashBox()->add(CashFactory::factory(100), 10);
        $this->jihanki->getCashBox()->add(CashFactory::factory(500), 10);
    }

    public function testAcceptCash()
    {
        $this->jihanki->acceptCash(10);
        $this->assertEquals(10, $this->jihanki->getAcceptedCashAmount());

        $this->jihanki->acceptCash(50);
        $this->assertEquals(60, $this->jihanki->getAcceptedCashAmount());

        $this->jihanki->acceptCash(100);
        $this->assertEquals(160, $this->jihanki->getAcceptedCashAmount());

        $this->jihanki->acceptCash(500);
        $this->assertEquals(660, $this->jihanki->getAcceptedCashAmount());

        $this->jihanki->acceptCash(1000);
        $this->assertEquals(1660, $this->jihanki->getAcceptedCashAmount());
    }

    public function testClearAccpetedCash()
    {
        $this->jihanki->acceptCash(1000);
        $this->jihanki->clearAcceptedCash();
        $this->assertEquals(0, $this->jihanki->getAcceptedCashAmount());
    }
    
    public function testAvailableList()
    {
        $this->jihanki->getStock()->add(2, new Item('Ayataka', 150), 5);
        $this->jihanki->getStock()->add(1, new Item('Coke', 120), 5);

        $this->jihanki->acceptCash(100);
        $this->assertEquals(array(), $this->jihanki->getAvailableList());
        $this->jihanki->acceptCash(10);
        $this->jihanki->acceptCash(10);
        $this->assertEquals(array(1), $this->jihanki->getAvailableList());
        $this->jihanki->acceptCash(50);
        $this->assertEquals(array(1,2), $this->jihanki->getAvailableList());
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testSellException()
    {
        $this->jihanki->getStock()->add(1, new Item('Coke', 120), 5);
        $this->jihanki->acceptCash(100);
        $this->jihanki->sell(1);
    }

    public function testSalesHistory()
    {
        $this->jihanki->getStock()->add(1, new Item('Coke', 120), 5);
        $this->jihanki->getStock()->add(2, new Item('Ayataka', 150), 5);

        $this->jihanki->acceptCash(1000);
        $this->jihanki->sell(1);
        $this->jihanki->sell(2);
        $this->jihanki->sell(1);

        $this->assertEquals(array(
            array('id' => 1, 'name' => 'Coke', 'sold' => 120),
            array('id' => 2, 'name' => 'Ayataka', 'sold' => 150),
            array('id' => 1, 'name' => 'Coke', 'sold' => 120),
        ), $this->jihanki->getSalesHistory());

        $this->assertEquals(240, $this->jihanki->getSales(1));
        $this->assertEquals(150, $this->jihanki->getSales(2));
        $this->assertEquals(390, $this->jihanki->getSales());
    }

    public function testCashBox()
    {
        $this->jihanki->getStock()->add(1, new Item('Coke', 120), 5);
        $this->jihanki->getStock()->add(2, new Item('Ayataka', 150), 5);

        $this->jihanki->acceptCash(1000);

        $expect = new \SplObjectStorage();
        $expect[CashFactory::factory(1000)] = 1;
        $expect[CashFactory::factory(500)] = 10;
        $expect[CashFactory::factory(100)] = 10;
        $expect[CashFactory::factory(50)] = 10;
        $expect[CashFactory::factory(10)] = 10;
        $this->assertEquals($expect, $this->jihanki->getCashBox()->getBox());

        $this->jihanki->sell(1);
        $this->jihanki->sell(2);
        $this->jihanki->sell(1);

        $this->assertEquals(610, $this->jihanki->getAcceptedCashAmount());

        $expect = new \SplObjectStorage();
        $expect[CashFactory::factory(500)] = 1;
        $expect[CashFactory::factory(100)] = 1;
        $expect[CashFactory::factory(10)] = 1;
        $this->assertEquals($expect, $this->jihanki->payoutChange());

    }

    public function testCannotPayoutChanges()
    {
        $this->jihanki->getCashBox()->reset();
        $this->jihanki->getStock()->add(1, new Item('Coke', 120), 5);
        $this->jihanki->getStock()->add(2, new Item('Ayataka', 150), 5);

        $this->jihanki->getCashBox()->add(CashFactory::factory(50), 1);

        $this->jihanki->acceptCash(100);
        $this->jihanki->acceptCash(100);
        $this->assertEquals(array(2), $this->jihanki->getAvailableList());
    }

    public function testMakeSurePayoutChanges()
    {
        $this->jihanki->getCashBox()->reset();
        $this->jihanki->getStock()->add(1, new Item('Coke', 120), 5);

        // 1000 = 100x10 
        $this->jihanki->acceptCash(100);
        $this->jihanki->acceptCash(100);
        $this->jihanki->acceptCash(100);
        $this->jihanki->acceptCash(100);
        $this->jihanki->acceptCash(100);
        $this->jihanki->acceptCash(100);
        $this->jihanki->acceptCash(100);
        $this->jihanki->acceptCash(100);
        $this->jihanki->acceptCash(100);
        $this->jihanki->acceptCash(100);

        $expect = new \SplObjectStorage();
        $expect[CashFactory::factory(100)] = 10;
        $this->assertEquals($expect, $this->jihanki->payoutChange());
    }
}
