<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Response;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Validation\UnauthorizedException;
use Illuminate\Validation\ValidationException;
use Myshop\Common\Dto\ErrorDto;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Tymon\JWTAuth\Exceptions\InvalidClaimException;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\PayloadException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\UserNotDefinedException;

class Handler extends ExceptionHandler
{
    protected $dontReport = [
        AuthenticationException::class,
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        TokenMismatchException::class,
        ValidationException::class,
    ];

    final public function render($request, Exception $e)
    {
        if ($request->is('api/*')) {
            $errorDto = $this->mapException($e);

            return response()->json($errorDto, $errorDto->getCode());
        }

        return parent::render($request, $e);
    }

    final protected function unauthenticated($request, AuthenticationException $e)
    {
        if ($request->expectsJson()) {
            $errorDto = new ErrorDto(
                Response::HTTP_UNAUTHORIZED,
                '사용자를 식별할 수 없습니다.',
                $e->getMessage()
            );

            return response()->json($errorDto, $errorDto->getCode());
        }

        return redirect()->guest(route('login'));
    }

    private function mapException(Exception $e)
    {
        $code = method_exists($e, 'getStatusCode')
            ? $e->getStatusCode() : Response::HTTP_INTERNAL_SERVER_ERROR;
        $description = $e->getMessage() ?? 'Unknown Error';

        $errorDto = new ErrorDto($code, '알 수 없는 오류가 발생했습니다.', $description);

        if ($e instanceof JWTException) {
            list($code, $message) = $this->mapJwtException($e);
            $errorDto = new ErrorDto($code, $message, $e->getMessage());
        }

        if ($e instanceof UnauthorizedHttpException
            || $e instanceof UnauthorizedException) {
            $errorDto = new ErrorDto(
                Response::HTTP_UNAUTHORIZED,
                '사용자를 식별할 수 없습니다.',
                $e->getMessage()
            );
        }

        if ($e instanceof AuthorizationException
            || $e instanceof AuthenticationException) {
            $errorDto = new ErrorDto(
                Response::HTTP_FORBIDDEN,
                '권한이 없습니다.',
                $e->getMessage()
            );
        }

        if ($e instanceof ModelNotFoundException) {
            $errorDto = new ErrorDto(
                Response::HTTP_NOT_FOUND,
                '요청하신 리소스를 찾을 수 없습니다.',
                $e->getMessage()
            );
        }

        if ($e instanceof ValidationException) {
            $errorDto = new ErrorDto(
                Response::HTTP_UNPROCESSABLE_ENTITY,
                '유효하지 않은 데이터입니다.',
                $e->validator->getMessageBag()->first()
            );
        }

        return $errorDto;
    }

    private function mapJwtException(JWTException $e)
    {
        if ($e instanceof TokenInvalidException || $e instanceof InvalidClaimException) {
            return [Response::HTTP_BAD_REQUEST, '토큰이 유효하지 않습니다.'];
        }

        if ($e instanceof TokenExpiredException) {
            return [Response::HTTP_BAD_REQUEST, '토큰이 만료되었습니다.'];
        }

        if ($e instanceof PayloadException) {
            return [Response::HTTP_BAD_REQUEST, '값을 임의로 변경할 수 없습니다.'];
        }

        if ($e instanceof UserNotDefinedException) {
            return [Response::HTTP_NOT_FOUND, '사용자를 식별할 수 없습니다.'];
        }

        return [0, ''];
    }
}
