<?php

class Product
{
    public $id;
    public $name;
    public $price;
    public $stock;
    public $access_level;
    public $category;

    /** Constructor
     * @param string $name
     * @param double $price
     * @param int $stock
     * @param int $access_level
     * @param string | int $category
     * @param int $id optionnal
     */
    public function __construct($name, $price, $stock, $access_level, $category, $id = null, )
    {
        $this->id = $id;
        $this->name = $name;
        $this->price = $price;
        $this->stock = $stock;
        $this->access_level = $access_level;
        $this->category = $category;
    }

}