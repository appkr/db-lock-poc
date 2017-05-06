<?php

namespace App\Http\Requests\Product;

use App\Http\Requests\BaseRequest;

class UpdateProductRequest extends BaseRequest
{
    public function authorize()
    {
        return $this->user()->isAdmin();
    }

    public function rules()
    {
        return [
            'title' => 'string|min:1',
            'stock' => 'integer|min:0',
            'price' => 'integer:min:10',
            'description' => 'string|min:1',
        ];
    }
}
