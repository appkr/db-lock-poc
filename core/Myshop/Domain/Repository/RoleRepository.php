<?php

namespace Myshop\Domain\Repository;

use Illuminate\Support\Collection;
use Myshop\Common\Model\DomainRole;
use Myshop\Domain\Model\Role;

interface RoleRepository
{
    public function all(): Collection;
    public function findById(int $id): Role;
    public function findByName(DomainRole $roleName): Role;
    public function save(Role $role): void;
    public function delete(Role $role): void;
}