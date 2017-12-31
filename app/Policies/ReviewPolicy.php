<?php

namespace App\Policies;

use Myshop\Common\Model\DomainPermission;
use Myshop\Domain\Model\User;
use Myshop\Domain\Model\Review;

class ReviewPolicy
{
    public function before(User $user, $ability)
    {
        if ($user->hasPermission(DomainPermission::MANAGE_REVIEW())) {
            return true;
        }
    }

    public function update(User $user, Review $review)
    {
        return $review->isOwnedBy($user);
    }

    public function destroy(User $user, Review $review)
    {
        return $review->isOwnedBy($user);
    }
}
