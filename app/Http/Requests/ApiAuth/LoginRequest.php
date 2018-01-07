<?php

namespace App\Http\Requests\ApiAuth;

use App\Http\Requests\BaseRequest;

class LoginRequest extends BaseRequest
{
    public function rules()
    {
        return [
            'email' => [
                'required',
                'email',
            ],
            'password' => [
                'required'
            ],
        ];
    }
}
