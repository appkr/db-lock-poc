<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class Handler extends ExceptionHandler
{
    protected $dontReport = [
        \Illuminate\Auth\AuthenticationException::class,
        \Illuminate\Auth\Access\AuthorizationException::class,
        \Symfony\Component\HttpKernel\Exception\HttpException::class,
        \Illuminate\Database\Eloquent\ModelNotFoundException::class,
        \Illuminate\Session\TokenMismatchException::class,
        \Illuminate\Validation\ValidationException::class,
    ];

    final public function render($request, Exception $e)
    {
        if ($request->is('api/*')) {
            $code = $statusCode = method_exists($e, 'getStatusCode')
                ? $e->getStatusCode() : Response::HTTP_INTERNAL_SERVER_ERROR;
            $message = $e->getMessage() ?? '알 수 없는 오류가 발생했습니다.';
            $description = null;

            if ($e instanceof ValidationException) {
                $code = $statusCode = Response::HTTP_UNPROCESSABLE_ENTITY;
                $message = '유효하지 않은 데이터입니다.';
                $description = $e->validator->getMessageBag()->first();
            }

            if ($e instanceof ModelNotFoundException) {
                $message = '요청하신 리소스를 찾을 수 없습니다.';
                $code = $statusCode = Response::HTTP_NOT_FOUND;
                $description = $e->getMessage();
            }

            if ($e instanceof AuthorizationException) {
                $message = '권한이 없습니다.';
                $code = $statusCode = Response::HTTP_FORBIDDEN;
                $description = $e->getMessage();
            }

            return response()->json([
                'code' => $code,
                'message' => $message,
                'description' => $description,
            ], $statusCode);
        }

        return parent::render($request, $e);
    }

    final protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            $code = $statusCode = Response::HTTP_UNAUTHORIZED;
            return response()->json([
                'code' => $code,
                'error' => '사용자를 식별할 수 없습니다.'
            ], $statusCode);
        }

        return redirect()->guest(route('login'));
    }
}
