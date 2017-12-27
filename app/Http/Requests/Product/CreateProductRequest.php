<?php

namespace App\Http\Requests\Product;

use App\Http\Requests\BaseRequest;
use Myshop\Common\Dto\ProductDto;

class CreateProductRequest extends BaseRequest
{
    public function rules()
    {
        return [
            'title' => [
                'required',
                'string',
            ],
            'stock' => [
                'required',
                'integer',
                'min:0',
            ],
            'price' => [
                'required',
                'integer',
                'min:0',
            ],
            'description' => [
                'required',
                'string',
            ],
        ];
    }

    public function getProductDto()
    {
        return new ProductDto(
            $this->getValue('title'),
            $this->getValue('stock'),
            $this->getMoney('price'),
            $this->getValue('description')
        );
    }
}
