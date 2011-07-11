<?php

namespace Gilbite\Jihanki\Tests;

use Gilbite\Jihanki\Jihanki;
use Gilbite\Jihanki\Money\AcceptableCashFactory as CashFactory;

class JihankiTest extends \PHPUnit_Framework_TestCase
{
    protected $jihank = null;

    public function setUp()
    {
        $this->jihanki = new Jihanki();
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
    
}
