<?php

namespace App\Http\Requests\Product;

use App\Http\Requests\BaseRequest;
use Myshop\Common\Dto\ProductDto;
use Myshop\Common\Model\Money;

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
