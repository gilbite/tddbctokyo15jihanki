<?php
namespace Gilbite\Jihanki;

use Gilbite\Jihanki\Money\AcceptableCashFactory as CashFactory;
use Gilbite\Jihanki\Stock\Stock;
use Gilbite\Jihanki\Money\Box;
use Gilbite\Jihanki\Stock\Item;

class Jihanki
{
    protected $acceptedCash = 0;
    protected $stock        = null;
    protected $cashBox      = null;
    protected $salesHistory = array();

    public function __construct(Stock $stock, Box $cashBox)
    {
        $this->stock = $stock;
        $this->cashBox = $cashBox;
    }

    /**
     * acceptCash 
     * 
     * @param integer $money 
     * @return void
     */
    public function acceptCash($money)
    {
        $this->getCashBox()->add(CashFactory::factory($money));
        $this->acceptedCash += $money;
    }

    /**
     * getAcceptedCashAmount 
     * 
     * @return integer
     */
    public function getAcceptedCashAmount()
    {
        return $this->acceptedCash;
    }

    /**
     * clearAcceptedCash 
     * 
     * @return void
     */
    public function clearAcceptedCash()
    {
        $this->acceptedCash = 0;
    }

    public function getStock()
    {
        return $this->stock;
    }

    public function getCashBox()
    {
        return $this->cashBox;
    }

    /**
     * getAvailableList 
     *   returns list of available ids with current cash
     * 
     * @access public
     * @return array sorted
     */
    public function getAvailableList()
    {
        $ret = array();
        foreach ($this->getStock()->getIdsInStock() as $id) {
            if ($this->getStock()->getItem($id)->getPrice() <= $this->getAcceptedCashAmount() ) {
                $change = $this->getAcceptedCashAmount() - $this->getStock()->getItem($id)->getPrice();
                if ($this->getCashBox()->isChangeAvailable($change)) {
                    $ret[] = $id;
                }
            }
        }
        sort($ret);

        return $ret;
    }

    public function sell($id)
    {
        if (!in_array($id, $this->getAvailableList(), true)) {
            throw new \RuntimeException('stock is 0 or cash is short');
        }

        $this->getStock()->reduce($id);
        $this->acceptedCash -= $this->getStock()->getItem($id)->getPrice();

        $this->salesHistory[] = array(
            'id'   => $id,
            'name' => $this->getStock()->getItem($id)->getName(),
            'sold' => $this->getStock()->getItem($id)->getPrice(),
        );
    }

    public function payoutChange()
    {
        $change = $this->getCashBox()->payoutChange($this->getAcceptedCashAmount());
        return $change;
    }

    public function getSalesHistory()
    {
        return $this->salesHistory;
    }

    public function getSales($id = null)
    {
        $ret = array();
        foreach ($this->getSalesHistory() as $raw) {
            $_id = $raw['id'];
            if (!isset($ret[$_id])) {
                $ret[$_id] = 0;
            }
            $ret[$_id] += $raw['sold'];
        }

        if (null === $id) {
            return array_sum($ret);
        } else {
            return isset($ret[$id]) ? $ret[$id] : 0;
        }

    }
}
