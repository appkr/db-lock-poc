<?php

namespace Myshop\Domain\Repository;

use Myshop\Common\Model\QueryOperator;
use Myshop\Domain\Model\User;

interface UserRepository
{
    public function findById(int $id) : User;
    public function findByName(string $name, QueryOperator $operator = null): User;
    public function findByEmail(string $email, QueryOperator $operator = null): User;
    public function save(User $user);
    public function delete(User $user);
}