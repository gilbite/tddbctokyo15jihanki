<?php

namespace Gilbite\Jihanki\Stock;

class Item
{

    protected $name = '';
    protected $price = 0;
    
    /**
     * constructor
     * 
     * @param string $name 
     * @param integer $price 
     */
    function __construct($name, $price)
    {
        if (!is_string($name)) {
            throw new \InvalidArgumentException('item name must be string');
        }

        if (!is_int($price)) {
            throw new \InvalidArgumentException('item price must be integer');
        }

        $this->name  = $name;
        $this->price = $price;
    }

    /**
     * getName 
     * 
     * @return string
     */
    public function getName() {
        return $this->name;
    }
    
    /**
     * getPrice 
     * 
     * @return price
     */
    public function getPrice() {
        return $this->price;
    }
    
}

