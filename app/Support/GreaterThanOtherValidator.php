<?php

namespace App\Support;

use Carbon\Carbon;
use Illuminate\Contracts\Validation\Validator;
use Request;

class GreaterThanOtherValidator
{
    /*
     * Valid values
     * 2017-12
     * 2017-12-01
     * 2017-12-01 10:10
     * 2017-12-01 10:10:10
     */
    const DATETIME_REGEX = '/^(?<Y>\d{4})-(?<m>\d{2})-?(?<d>\d{2})?\s?(?<Hi>\d{2}:d{2})?:?(?<s>\d{2})?$/';

    private $type;

    /**
     * $attribute 필드 값이 $params[0] 값보다 큰 값인지 검사합니다.
     *
     * @param string $attribute
     * @param string|array $value
     * @param array $params
     * @param Validator $validator
     * @return bool
     */
    public function validate(string $attribute, $value, array $params, Validator $validator)
    {
        $other = Request::input($params[0]);

        return $this->normalize($value) > $this->normalize($other);
    }

    private function normalize($value)
    {
        $guess = gettype($value);

        if (is_array($value)) {
            $guess = 'array';
            $value = count($value);
        } else {
            if (is_numeric($value)) {
                $guess = 'numeric';
                $value *= 1;
            } elseif (preg_match(self::DATETIME_REGEX, $value)) {
                $guess = 'date';
                $value = new Carbon($value);
            } else {
                $value = mb_strlen($value);
            }
        }

        if ($this->type && $this->type !== $guess) {
            // Data type should be identical
            // null > null => false
            return null;
        }

        $this->type = $guess;

        return $value;
    }
}