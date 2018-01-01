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

    public function delete(User $user, Review $review)
    {
        // NOTE. 컨트롤러 함수명이 아니라, Gate에서 정의한 상수를 함수명으로 사용해야 함을 주의할 것.
        return $review->isOwnedBy($user);
    }
}
