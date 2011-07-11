<?php

namespace Gilbite\Jihanki\Stock;

class Stock
{
    protected $item   = array();
    protected $amount = array();

    
    public function add($id, Item $item, $amount)
    {
        if (!is_int($id) || $id <= 0) {
            throw new \InvalidArgumentException('id must be integer and > 0');
        }

        if (!is_int($amount) || $amount <= 0 ) {
            throw new \InvalidArgumentException('amount must be integer and > 0');
        }

        if (isset($this->item[$id])) {
            if ($this->item[$id] != $item && $this->amount[$id] > 0) {
                throw new \InvalidArgumentException(sprintf('id:%d is already set for %s', $id, $this->item[$id]->getName()));
            }
        } else {
            $this->amount[$id] = 0;
        }

        $this->item[$id]    = $item;
        $this->amount[$id] += $amount;

    }

    public function reduce($id)
    {
        $this->checkId($id);
        if ($this->getAmount($id) <= 0) {
            throw new \RuntimeException('item:' . $this->getItem($id)->getName() . ' is not in stock');
        }

        $this->amount[$id]--;
    }

    /**
     * getAmount 
     * 
     * @param integer $id 
     * @access public
     * @return integer
     */
    public function getAmount($id)
    {
        $this->checkId($id);
        return $this->amount[$id];
    }

    /**
     * getItem 
     * 
     * @param mixed $id 
     * @access public
     * @return Gilbite\Jihianki\Stock\Item
     */
    public function getItem($id)
    {
        $this->checkId($id);
        return $this->item[$id];
    }

    public function getIds()
    {
        return array_keys($this->item);
    }

    protected function checkId($id)
    {
        if (!isset($this->item[$id])) {
            throw new \InvalidArgumentException('$id:' . $id . ' is not set yet');
        }
    }
}
