<?php

namespace App\Http\Requests\Review;

use App\Http\Requests\BaseRequest;
use Myshop\Common\Dto\ReviewSearchParam;
use Myshop\Common\Model\ReviewSortKey;
use Myshop\Common\Model\SortDirection;

class ListReviewRequest extends BaseRequest
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
            'user_id' => [
                'integer',
            ],

            // 정렬
            'sort_key' => [
                'in:' . implode(',', ReviewSortKey::keys()),
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

    public function getReviewSearchParam()
    {
        return new ReviewSearchParam(
            $this->getValue('q'),
            $this->getValue('user_id'),
            new ReviewSortKey(
                $this->getValue('sort_key', ReviewSortKey::CREATED_AT)
            ),
            new SortDirection(
                $this->getValue('sort_direction', SortDirection::DESC)
            ),
            $this->getValue('page', 1),
            $this->getValue('size', 10)
        );
    }
}
