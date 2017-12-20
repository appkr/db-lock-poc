<?php

namespace App\Policies\Review;

use Myshop\Domain\Model\User;
use Myshop\Domain\Model\Review;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReviewPolicy
{
    use HandlesAuthorization;

    public function before(User $user, $ability)
    {
        if ($user->isAdmin()) {
            return true;
        }
    }

    public function update(User $user, Review $review)
    {
        return $review->isBelongsToUser($user);
    }

    public function delete(User $user, Review $review)
    {
        return $review->isBelongsToUser($user);
    }
}
