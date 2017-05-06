<?php

namespace App\Http\Requests\Review;

use App\Http\Requests\BaseRequest;

class CreateReviewRequest extends BaseRequest
{
    public function rules()
    {
        return [
            'title' => 'required|string|min:1',
            'content' => 'required|string|min:1',
        ];
    }
}
