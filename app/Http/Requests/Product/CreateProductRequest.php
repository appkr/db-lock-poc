<?php

namespace App\Http\Requests\Product;

use App\Http\Requests\BaseRequest;

class CreateProductRequest extends BaseRequest
{
    public function authorize()
    {
        return $this->user()->isAdmin();
    }

    public function rules()
    {
        return [
            'title' => 'required|string|min:1',
            'stock' => 'required|integer|min:0',
            'price' => 'required|integer:min:10',
            'description' => 'required|string|min:1',
        ];
    }
}
