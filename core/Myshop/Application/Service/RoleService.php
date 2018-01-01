<?php

namespace Myshop\Application\Service;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Support\Collection;
use Myshop\Common\Model\DomainRole;
use Myshop\Domain\Model\Role;
use Myshop\Domain\Model\User;
use Myshop\Domain\Repository\PermissionRepository;
use Myshop\Domain\Repository\RoleRepository;
use Myshop\Domain\Repository\UserRepository;

class RoleService
{
    private $roleRepository;
    private $permissionRepository;
    private $userRepository;
    private $permissionService;
    private $config;

    public function __construct(
        RoleRepository $roleRepository,
        PermissionRepository $permissionRepository,
        UserRepository $userRepository,
        PermissionService $permissionService,
        Repository $config
    ) {
        $this->roleRepository = $roleRepository;
        $this->permissionRepository = $permissionRepository;
        $this->userRepository = $userRepository;
        $this->permissionService = $permissionService;
        $this->config = $config;
    }

    /**
     * @param DomainRole $roleName
     * @param array|UserPermission[] $givenPermissionNames
     * @param string|null $guardName
     * @return Role
     */
    public function createRole(
        DomainRole $roleName,
        array $givenPermissionNames = [],
        $guardName = null
    ) {
        $guardName = $guardName ?: $this->config->get('auth.defaults.guard');

        $role = new Role;
        $role->name = $roleName;
        $role->guard = $guardName;
        // NOTE. 외래키 연결에 ID가 사용되므로 DB 저장을 먼저 해야 합니다.
        $this->roleRepository->save($role);

        foreach ($givenPermissionNames as $permissionName) {
            $permission = $this->permissionRepository->findByName($permissionName);
            $this->permissionService->attachPermissionToRole($role, $permission);
        }

        return $role->fresh();
    }

    public function assignRoleToUser(User $user, Role $role)
    {
        $user->roles()->attach($role);

        // Role이 Permission 보다 상위 개념이므로 사용자에게 Role을 할당하면,
        // Role에 연결된 Permission도 해당 User에게 같이 할당되어야 합니다.
        $permissions = $role->permissions;
        foreach ($permissions as $permission) {
            $this->permissionService->grantPermissionToUser($user, $permission);
        }

        $this->userRepository->save($user);
    }

    public function removeRoleFromUser(User $user, Role $role)
    {
        $user->roles()->detach($role);

        $permissions = $role->permissions;
        foreach ($permissions as $permission) {
            $this->permissionService->revokePermissionFromUser($user, $permission);
        }

        $this->userRepository->save($user);
    }

    /**
     * @param User $user
     * @param array|Role[]|Collection $roles
     */
    public function syncRolesOfUser(User $user, $roles)
    {
        $roleIds = $this->getRoleIds($roles);
        $user->roles()->sync($roleIds);

        $this->permissionService->syncPermissionsOfUser(
            $user,
            $this->getAllPermissionsFromRoles($roles)
        );

        $this->userRepository->save($user);
    }

    /**
     * @param array|Role[]|Collection $roles
     * @return array
     */
    private function getRoleIds($roles)
    {
        return ($roles instanceof Collection)
            ? $roles->pluck('id')->toArray()
            : array_pluck($roles, 'id');
    }

    /**
     * @param array|Role[]|Collection $roles
     * @return Collection
     */
    private function getAllPermissionsFromRoles($roles)
    {
        $mergedPermissions = new Collection([]);

        /** @var Role $role */
        foreach ($roles as $role) {
            $mergedPermissions->merge($role->permissions->all());
        }

        return $mergedPermissions->values();
    }
}