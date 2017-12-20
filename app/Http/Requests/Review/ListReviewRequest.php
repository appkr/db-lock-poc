<?php

namespace App\Http\Requests\Review;

use App\Http\Requests\BaseRequest;
use Myshop\Common\Dto\ReviewSearchParam;

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
            'sort_direction' => 'in:asc,desc',

            // 페이징
            'page' => 'integer',
            'size' => 'integer',
        ];
    }

    public function getReviewSearchParam()
    {
        return new ReviewSearchParam(
            $this->getValue('q'),
            $this->getValue('user_id'),
            $this->transformSortBy(),
            $this->getValue('sort_direction', 'desc'),
            $this->getValue('page', 1),
            $this->getValue('size', 10)
        );
    }

    private function transformSortBy()
    {
        $map = [
            'date' => 'created_at',
        ];

        $givenByUser = $this->getValue('sort_by');

        return array_key_exists($givenByUser, $map)
            ? $map[$givenByUser] : null;
    }
}
