<?php

namespace Myshop\Common\Model;

/**
 * Class Money
 * @package Myshop\Common\Model
 *
 * $a = new Myshop\Common\Model\Money(100);
 * $b = new Myshop\Common\Model\Money(100);
 * $a == $b // true
 * $a === $b // false
 * $a->isEqualTo($b); // true
 */
class Money implements \JsonSerializable
{
    private $value;

    public function __construct(int $value = 0)
    {
        $this->value = $value;
    }

    public function add(Money $other)
    {
        return new Money($this->value + $other->getAmount());
    }

    public function subtract(Money $other)
    {
        return new Money($this->value - $other->getAmount());
    }

    public function multiply(int $quantity)
    {
        return new Money($this->value * $quantity);
    }

    public function getAmount()
    {
        return $this->value;
    }

    public function withCurrency(string $currency = '원')
    {
        return number_format($this->value) . $currency;
    }

    public function isEqualTo(Money $other)
    {
        return $this->value === $other->getAmount();
    }

    public function isBiggerThan(Money $other)
    {
        return $this->value > $other->getAmount();
    }

    public function isEqualOrBiggerThan(Money $other)
    {
        return $this->value >= $other->getAmount();
    }

    public function isSmallerThan(Money $other)
    {
        return $this->value < $other->getAmount();
    }

    public function isEqualOrSmallerThan(Money $other)
    {
        return $this->value <= $other->getAmount();
    }

    public function __toString()
    {
        return (string) $this->value;
    }

    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    function jsonSerialize()
    {
        return $this->value;
    }
}