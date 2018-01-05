<?php

namespace Myshop\Common\Dto;

use JsonSerializable;

/**
 * @SWG\Definition(
 *     type="object",
 *     required={"code", "message"},
 * )
 */
final class ErrorDto implements JsonSerializable
{
    /**
     * @SWG\Property(format="int32", description="에러 코드", example=400)
     * @var int $code
     */
    private $code;

    /**
     * @SWG\Property(description="에러 메시지", example="Bad Request")
     * @var string $message
     */
    private $message;

    /**
     * @SWG\Property(description="에러 디테일", example="필수값이 누락되었습니다")
     * @var string $description
     */
    private $description;

    /**
     * @SWG\Property(description="에러 번호", example="02bf6f6b461c47d9b309e6651d07dd19")
     * @var string $exceptionId
     */
    private $exceptionId;

    public function __construct(
        int $code = 0,
        string $message = '',
        string $description = '',
        string $exceptionId = null
    ) {
        $this->code = $code;
        $this->message = $message;
        $this->description = $description;
        $this->exceptionId = $exceptionId;
    }

    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        return [
            'code' => $this->code,
            'message' => $this->message,
            'description' => $this->description,
            'exceptionId' => $this->exceptionId,
        ];
    }

    public function getCode()
    {
        return $this->code;
    }

    public function setCode(int $code)
    {
        $this->code = $code;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function setMessage(string $message)
    {
        $this->message = $message;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription(string $description)
    {
        $this->description = $description;
    }

    public function getExceptionId()
    {
        return $this->exceptionId;
    }

    public function setExceptionId(string $exceptionId)
    {
        $this->exceptionId = $exceptionId;
    }
}
