<?php

namespace Myshop\Common\Dto;

use Myshop\Common\Model\Money;

class ProductDto
{
    private $title;
    private $stock;
    private $price;
    private $description;

    public function __construct(
        string $title = null,
        int $stock = null,
        Money $price = null,
        string $description = null
    ) {
        $this->title = $title;
        $this->stock = $stock;
        $this->price = $price;
        $this->description = $description;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getStock()
    {
        return $this->stock;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function getDescription()
    {
        return $this->description;
    }
}