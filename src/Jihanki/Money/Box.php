<?php

namespace Gilbite\Jihanki\Money;

use Gilbite\Jihanki\Money\Cash;

class Box
{
    protected $box = null;

    public function __construct()
    {
        $this->box = new \SplObjectStorage();
    }

    public function getBox()
    {
        return $this->box;
    }

    public function add(Cash $cash, $amount = 1)
    {
        if (!is_int($amount)) {
            throw new \InvalidArgumentException('amount must be integer');
        }

        if (!isset($this->box[$cash])) {
            $this->box[$cash] = 0;
        }

        $this->box[$cash] += $amount;
    }

    public function reduce(Cash $cash, $amount = 1)
    {
        if (!is_int($amount)) {
            throw new \InvalidArgumentException('amount must be integer');
        }

        if (!isset($this->box[$cash]) || $this->box[$cash] < $amount) {
            throw new \RuntimeException('not enough cash to reduce exists');
        }

        $this->box[$cash] -= $amount;
    }

    public function reset()
    {
        $this->box = new \SplObjectStorage();
    }

    public function getAmount()
    {
        $ret = 0;
        foreach ($this->box as $cash) {
            $ret += $cash->getValue() * $this->box[$cash];
        }

        return $ret;
    }
    
    public function calcChange($money)
    {
        if (!is_int($money)) {
            throw new \InvalidArgumentException('money must be integer');
        }

        $result  = clone $this->box;
        $change = new \SplObjectStorage();

        if (0 === $money) {
            return $change;
        }

        $tmp = array();
        foreach ($this->box as $cash) {
            $tmp[$cash->getValue()] = $cash;
        }
        ksort($tmp);
        $tmp = array_reverse($tmp);


        foreach ($tmp as $cash) {
            while ($cash->getValue() <= $money && $result[$cash] > 0) {
                if (!isset($change[$cash])) {
                    $change[$cash] = 0;
                }
                if ($change[$cash] < $this->getMaxOutPerCash($cash)) {
                    $change[$cash] += 1;
                    $result[$cash] -= 1;
                    $money -= $cash->getValue();
                } else {
                    break;
                }

                if (0 === $money) {
                    return $change;
                } else if ($money < 0) {
                    return false;
                }
            }
        }

        return false;
    }


    public function getMaxOutPerCash(Cash $cash)
    {
        $unit =  pow(10, strlen((string )$cash->getValue()));
        $quotient = (int )$unit / (int )$cash->getValue();

        if (0 === $unit % $cash->getValue()) {
            return $quotient - 1;
        } else {
            return $quotient;
        }
        
    }

    public function isChangeAvailable($money)
    {
        return (bool ) $this->calcChange($money);
    }

    public function payoutChange($money)
    {
        $change = $this->calcChange($money);

        foreach ($change as $cash) {
            $this->reduce($cash, $change[$cash]);
        }

        return $change;
    }
}
