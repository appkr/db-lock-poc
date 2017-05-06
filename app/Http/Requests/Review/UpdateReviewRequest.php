<?php

namespace App\Http\Requests\Review;

use App\Http\Requests\BaseRequest;
use Myshop\Common\Dto\ReviewDto;

class UpdateReviewRequest extends BaseRequest
{
    public function rules()
    {
        return [
            'title' => 'string|min:1',
            'content' => 'string|min:1',
        ];
    }

    public function getReviewDto()
    {
        return new ReviewDto(
            $this->getValue('title'),
            $this->getValue('content')
        );
    }
}
