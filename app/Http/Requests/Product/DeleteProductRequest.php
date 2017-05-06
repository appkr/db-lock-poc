<?php

namespace App\Http\Requests\Product;

use App\Http\Requests\BaseRequest;

class DeleteProductRequest extends BaseRequest
{
    public function authorize()
    {
        return $this->user()->isAdmin();
    }

    public function rules()
    {
        return [
            //
        ];
    }
}
