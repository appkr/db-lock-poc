<?php

namespace Myshop\Common\Dto;

use Myshop\Common\Model\ReviewSortKey;
use Myshop\Common\Model\SortDirection;

class ReviewSearchParam
{
    private $keyword;
    private $userId;
    private $sortKey;
    private $sortDirection;
    private $page;
    private $size;

    public function __construct(
        string $keyword = null,
        int $userId = null,
        ReviewSortKey $sortKey = null,
        SortDirection $sortDirection = null,
        int $page = null,
        int $size = null
    ) {
        $this->keyword = $keyword;
        $this->userId = $userId;
        // NOTE. Dto를 사용하는 리포지토리에서 아래 변수들은 값이 있다고 간주(Not NULL)하고 쿼리를 합니다.
        $this->sortKey = $sortKey ?: ReviewSortKey::CREATED_AT();
        $this->sortDirection = $sortDirection ?: SortDirection::DESC();
        $this->page = $page ?: 1;
        $this->size = $size ?: 10;
    }

    public function getKeyword()
    {
        return $this->keyword;
    }

    public function getUserId()
    {
        return $this->userId;
    }

    public function getSortKey()
    {
        return $this->sortKey;
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