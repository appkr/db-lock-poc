<?php

namespace Myshop\Infrastructure\ModelObserver;

use Myshop\Domain\Model\User;

class UserObserver
{
    public function deleting(User $user)
    {
        // TODO @appkr save() 호출 없이도 저장되지는 확인 필요
        $user->roles()->detach();
        $user->permissions()->detach();
    }
}