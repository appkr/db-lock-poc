<?php

namespace Myshop\Infrastructure\ModelObserver;

use Myshop\Domain\Model\User;

class UserObserver
{
    public function deleting(User $user)
    {
        $user->roles()->detach();
        $user->permissions()->detach();
    }
}