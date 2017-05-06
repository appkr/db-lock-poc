<?php

namespace Myshop\Common\Dto;

class ReviewDto
{
    private $title;
    private $content;

    public function __construct(
        string $title = null,
        string $content = null,
        Money $price = null,
        string $description = null
    ) {
        $this->title = $title;
        $this->content = $content;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getContent()
    {
        return $this->content;
    }
}