<?php
namespace Gilbite\Jihanki;

use Gilbite\Jihanki\Money\AcceptableCashFactory as CashFactory;
use Gilbite\Jihanki\Stock\Stock;
use Gilbite\Jihanki\Stock\Item;

class Jihanki
{
    protected $acceptedCash = array();
    protected $stock        = null;
    protected $salesHistory = array();

    public function __construct(Stock $stock)
    {
        $this->stock = new Stock();
    }

    /**
     * acceptCash 
     * 
     * @param integer $money 
     * @return void
     */
    public function acceptCash($money)
    {
        $this->acceptedCash[] = CashFactory::factory($money);
    }

    /**
     * getAcceptedCashAmount 
     * 
     * @return integer
     */
    public function getAcceptedCashAmount()
    {
        $ret = 0;
        foreach ($this->acceptedCash as $cash) {
            $ret += $cash->getValue();
        }

        return $ret;
    }

    /**
     * clearAcceptedCash 
     * 
     * @return void
     */
    public function clearAcceptedCash()
    {
        $this->acceptedCash = array();
    }

    public function getStock()
    {
        return $this->stock;
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
                $ret[] = $id;
            }
        }
        sort($ret);

        return $ret;
    }

    public function sell($id)
    {
        $this->getStock()->reduce($id);
        $this->salesHistory[] = array(
            'id'   => $id,
            'name' => $this->getStock()->getItem($id)->getName(),
            'sold' => $this->getStock()->getItem($id)->getPrice(),
        );
    }

    public function getSalesHistory()
    {
        return $this->salesHistory;
    }
}
