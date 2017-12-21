<?php

namespace App\Http\Requests\Product;

use App\Http\Requests\BaseRequest;
use Myshop\Common\Dto\ProductSearchParam;
use Myshop\Common\Model\PriceRange;
use Myshop\Common\Model\ProductSortKey;
use Myshop\Common\Model\SortDirection;

class ListProductRequest extends BaseRequest
{
    public function rules()
    {
        return [
            // 검색
            'q' => [
                'string',
                'min:1',
            ],

            // 필터
            'price_from' => [
                'integer',
                'min:0',
            ],
            'price_to' => [
                'integer',
                'greater_than_other:price_from',
            ],

            // 정렬
            'sort_key' => [
                'in:' . implode(',', ProductSortKey::keys()),
            ],
            'sort_direction' => [
                'in:' . implode(',', SortDirection::keys()),
            ],

            // 페이징
            'page' => [
                'integer',
                'min:1',
            ],
            'size' => [
                'integer',
                'min:1',
            ],
        ];
    }

    public function getProductSearchParam()
    {
        return new ProductSearchParam(
            $this->getValue('q'),
            new PriceRange(
                $this->getMoney('price_from'),
                $this->getMoney('price_to')
            ),
            new ProductSortKey(
                $this->getValue('sort_key', ProductSortKey::CREATED_AT)
            ),
            new SortDirection(
                $this->getValue('sort_direction', SortDirection::DESC)
            ),
            $this->getValue('page', 1),
            $this->getValue('size', 10)
        );
    }
}
