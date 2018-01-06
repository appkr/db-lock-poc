<?php

namespace App\Http\Middleware;

use Closure;
use Myshop\Domain\Model\User;
use Raven_Client;
use Tymon\JWTAuth\JWTGuard;

class SentryContext
{
    private $guard;
    private $sentry;

    public function __construct(JWTGuard $guard, Raven_Client $sentry)
    {
        $this->guard = $guard;
        $this->sentry = $sentry;
    }

    public function handle($request, Closure $next)
    {
        $this->sentry->user_context([
            'id' => null,
            'name' => 'Unknown',
            'roles' => [],
            'permissions' => [],
        ]);

        if ($this->guard->check()) {
            /** @var User $user */
            $user = $this->guard->user();
            $this->sentry->user_context([
                // TODO @appkr DUPLICATE. see \Myshop\Domain\Model\User::getJWTCustomClaims
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'roles' => $user->roles->implode('name', ','),
                'permissions' => $user->permissions->implode('name', ','),
            ]);
        }

        $this->sentry->tags_context(['instance' => env('EC2_INSTANCE_ID', gethostname())]);

        return $next($request);
    }
}
