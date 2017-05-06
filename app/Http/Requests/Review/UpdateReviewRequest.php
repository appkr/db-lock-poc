<?php

namespace App\Http\Requests\Review;

use App\Http\Requests\BaseRequest;

class UpdateReviewRequest extends BaseRequest
{
    public function rules()
    {
        return [
            'title' => 'string|min:1',
            'content' => 'string|min:1',
        ];
    }
}
