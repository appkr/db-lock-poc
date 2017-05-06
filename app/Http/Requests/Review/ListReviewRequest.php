<?php

namespace App\Http\Requests\Review;

use App\Http\Requests\BaseRequest;

class ListReviewRequest extends BaseRequest
{
    public function rules()
    {
        return [
            // 검색
            'q' => 'string|min:1',

            // 필터
            'user_id' => 'integer',

            // 정렬
            'sort_by' => 'in:date',
            'asc' => 'in:true,false',

            // 페이징
            'page' => 'integer',
            'size' => 'integer',
        ];
    }
}
