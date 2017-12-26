<?php

namespace App\Support;

use Illuminate\Contracts\Support\Arrayable;
use JsonSerializable;

abstract class AbstractScrambler
{
    abstract public function getPatterns(): array;
    abstract public function getPlaceholder(): string;

    public function scramble($data)
    {
        if ($data instanceof JsonSerializable && !is_array($data->jsonSerialize())) {
            return $data;
        }

        if ($data instanceof JsonSerializable && is_array($data->jsonSerialize())) {
            $data = $data->jsonSerialize();
        } elseif ($data instanceof Arrayable) {
            $data = $data->toArray();
        } elseif ($data instanceof stdClass) {
            $data = (array) $data;
        }

        if (! is_array($data)) {
            return $data;
        }

        $filtered = [];
        foreach ($data as $key => $value) {
            if (is_string($value)) {
                // TODO @appkr 문자열만 스크램블하면 되는가?
                foreach ($this->getPatterns() as $pattern) {
                    if (preg_match($pattern, $key)) {
                        $value = $this->getPlaceholder();
                    }
                }
            }

            $filtered[$key] = $this->scramble($value);
        }

        return $filtered;
    }
}