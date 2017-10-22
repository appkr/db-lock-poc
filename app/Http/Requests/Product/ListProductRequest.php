<?php

namespace App\Http\Requests\Product;

use App\Http\Requests\BaseRequest;
use Myshop\Common\Dto\ProductSearchParam;
use Myshop\Common\Model\Money;
use Myshop\Common\Model\PriceRange;

class ListProductRequest extends BaseRequest
{
    public function rules()
    {
        return [
            // 검색
            'q' => 'string|min:1',

            // 필터
            'price_from' => 'integer',
            'price_to' => 'integer|greater_than_other:price_from',

            // 정렬
            'sort_by' => 'in:date,price,stock',
            'asc' => 'in:true,false',

            // 페이징
            'page' => 'integer',
            'size' => 'integer',
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
            $this->transformSortBy(),
            $this->transformSortDirection(),
            $this->getValue('page'),
            $this->getValue('size')
        );
    }

    private function transformSortBy()
    {
        $map = [
            'date' => 'created_at',
            'price' => 'price',
            'stock' => 'stock',
        ];

        $givenByUser = $this->getValue('sort_by');

        return array_key_exists($givenByUser, $map)
            ? $map[$givenByUser] : 'created_at';
    }

    private function transformSortDirection()
    {
        $givenByUser = $this->getBoolean('asc');

        if (is_null($givenByUser)) {
            return 'desc';
        }

        return $givenByUser === true ? 'asc' : 'desc';
    }
}
