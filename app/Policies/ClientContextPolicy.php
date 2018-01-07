<?php

namespace App\Policies;

use App\Http\Exception\NotAllowedIpException;
use Myshop\Common\Dto\AdditionalUserContextDto;
use Myshop\Domain\Model\User;
use Symfony\Component\HttpFoundation\IpUtils;

class ClientContextPolicy
{
    public function check(User $user, AdditionalUserContextDto $dto)
    {
        $this->checkUserIp($user, $dto);
        // TODO @appkr Add additional check if required, e.g. checkUserAgent()
    }

    private function checkUserIp(User $user, AdditionalUserContextDto $dto)
    {
        $allowedIps = $user->allowed_ips ?: ['*'];

        if (in_array('*', $allowedIps, true)) {
            return;
        }

        $accessAllowed = IpUtils::checkIp($dto->getClientIp(), $allowedIps);

        if (! $accessAllowed) {
            throw new NotAllowedIpException;
        }
    }
}