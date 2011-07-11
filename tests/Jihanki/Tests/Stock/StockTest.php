<?php

namespace Gilbite\Jihanki\Tests\Stock;

use Gilbite\Jihanki\Jihanki;
use Gilbite\Jihanki\Stock\Item;
use Gilbite\Jihanki\Stock\Stock;

class StockTest extends \PHPUnit_Framework_TestCase
{
    protected $stock = null;

    public function setUp()
    {
        $this->stock = new Stock();
    }

    public function testAddStock()
    {
        $coke = new Item('Coke', 120);
        $this->stock->add(1, $coke, 5);

        $this->assertEquals(5, $this->stock->getAmount(1));
        $this->assertEquals($coke, $this->stock->getItem(1));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testAddStockException0Add()
    {
        $coke = new Item('Coke', 120);
        $this->stock->add(1, $coke, 0);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testAddStockExceptionMixed()
    {
        $coke = new Item('Coke', 120);
        $this->stock->add(1, $coke, 5);
        $pepsi = new Item('Pepsi', 120);
        $this->stock->add(1, $pepsi, 5);
    }

    public function testReduce()
    {
        $coke = new Item('Coke', 120);
        $this->stock->add(1, $coke, 5);

        $this->stock->reduce(1);
        $this->assertEquals(4, $this->stock->getAmount(1));
    }

    public function testReduceException()
    {
        $coke = new Item('Coke', 120);
        $this->stock->add(1, $coke, 1);
        $this->stock->reduce(1);

        try {
            $this->stock->reduce(1);
            $this->fail();
        } catch (\RuntimeException $e){
        }
    }
}
