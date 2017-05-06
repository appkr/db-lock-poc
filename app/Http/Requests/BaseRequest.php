<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BaseRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    /**
     * 입력 필드의 값을 조회합니다.
     *
     * @param string $key 조회할 입력 필드 이름.
     * @param mixed|null $default 해당 입력 필드가 없거나 값이 비어있을 때 사용할 기본 값.
     * @param callable|null $filter 조회한 입력값을 추가 조작하기 위한 필터.
     * @return array|null|string
     */
    protected function getValue(string $key, $default = null, callable $filter = null)
    {
        // User Action        => User Intent
        // 1. key NOT PRESENT => key의 값을 바꾸거나 조회 쿼리에 사용할 의도가 없음
        // 2. key=null        => key의 값을 null로 바꾸거나 값이 null인 레코드를 조회할 의도가 있음
        // 3. key=''          => key의 값을 공백으로 바꾸거나 값이 공백인 레코드를 조회할 의도가 있음
        // 4. key=value       => key의 값을 value로 바꾸거나 값이 value인 레코드를 조회할 의도가 있음

        // 2/3/4에 해당하면 사용자가 입력한 값을 그대로 이용하고
        // 1에 해당하면 $default로 넘겨 받은 값을 이용합니다.
        $value = $this->exists($key) ? $this->input($key) : $default;

        if (is_null($filter) || !is_callable($filter)) {
            return $value;
        }

        return call_user_func($filter, $value);
    }

    protected function getBoolean(string $key, $default = null)
    {
        return $this->getValue($key, $default, function ($value) {
            if (is_null($value)) {
                return null;
            }

            return filter_var($value, FILTER_VALIDATE_BOOLEAN);
        });
    }
}