<?php

namespace App\Http\Requests\Review;

use App\Http\Requests\BaseRequest;
use Myshop\Common\Dto\ReviewDto;

class CreateReviewRequest extends BaseRequest
{
    public function rules()
    {
        return [
            'title' => 'required|string|min:1',
            'content' => 'required|string|min:1',
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
