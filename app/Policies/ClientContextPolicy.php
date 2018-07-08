<?php

namespace App\Policies;

use App\ApplicationContext;
use App\Http\Exception\NotAllowedIpException;
use Myshop\Domain\Model\User;
use Symfony\Component\HttpFoundation\IpUtils;

class ClientContextPolicy
{
    private $appContext;

    public function __construct(ApplicationContext $appContext)
    {
        $this->appContext = $appContext;
    }

    public function check()
    {
        $user = $this->appContext->getUser();
        $clientIp = $this->appContext->getClientIp();
        $this->checkUserIp($user, $clientIp);
    }

    private function checkUserIp(User $user, $clientIp)
    {
        $allowedIps = $user->allowed_ips ?: ['*'];
        if (in_array('*', $allowedIps, true)) {
            return;
        }

        $accessAllowed = IpUtils::checkIp($clientIp, $allowedIps);
        if (! $accessAllowed) {
            throw new NotAllowedIpException;
        }
    }
}
