<?php

namespace Myshop\Common\Dto;

use Myshop\Common\Model\Money;

class ProductSearchParam
{
    private $keyword;
    private $priceFrom;
    private $priceTo;
    private $sortBy;
    private $sortDirection;
    private $page;
    private $size;

    public function __construct(
        string $keyword = null,
        Money $priceFrom = null,
        Money $priceTo = null,
        string $sortBy = 'created_at',
        string $sortDirection = 'desc',
        int $page = 1,
        int $size = 10
    ) {
        $this->keyword = $keyword;
        $this->priceFrom = $priceFrom;
        $this->priceTo = $priceTo;
        $this->sortBy = $sortBy;
        $this->sortDirection = $sortDirection;
        $this->page = $page;
        $this->size = $size;
    }

    public function getKeyword()
    {
        return $this->keyword;
    }

    public function getPriceFrom()
    {
        return $this->priceFrom;
    }

    public function getPriceTo()
    {
        return $this->priceTo;
    }

    public function getSortBy()
    {
        return $this->sortBy;
    }

    public function getSortDirection()
    {
        return $this->sortDirection;
    }

    public function getPage()
    {
        return $this->page;
    }

    public function getSize()
    {
        return $this->size;
    }
}