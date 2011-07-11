<?php
namespace Gilbite\Jihanki;

use Gilbite\Jihanki\Money\AcceptableCashFactory as CashFactory;

class Jihanki
{
    protected $acceptedCash = array();

    public function acceptCash($money)
    {
        $this->acceptedCash[] = CashFactory::factory($money);
    }

    public function getAcceptedCashAmount()
    {
        $ret = 0;
        foreach ($this->acceptedCash as $cash) {
            $ret += $cash->getValue();
        }

        return $ret;
    }

    public function clearAcceptedCash()
    {
        $this->acceptedCash = array();
    }
}
