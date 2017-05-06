<?php

namespace App\Http\Requests\Product;

use App\Http\Requests\BaseRequest;

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
}
