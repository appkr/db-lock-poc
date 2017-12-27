<?php

namespace Myshop\Application\Service;

use Illuminate\Contracts\Config\Repository as ConfigRepository;
use Illuminate\Support\Collection;
use Myshop\Common\Model\DomainPermission;
use Myshop\Domain\Model\Permission;
use Myshop\Domain\Model\Role;
use Myshop\Domain\Model\User;
use Myshop\Domain\Repository\PermissionRepository;
use Myshop\Domain\Repository\RoleRepository;
use Myshop\Domain\Repository\UserRepository;

class PermissionService
{
    private $roleRepository;
    private $permissionRepository;
    private $userRepository;
    private $config;

    public function __construct(
        RoleRepository $roleRepository,
        PermissionRepository $permissionRepository,
        UserRepository $userRepository,
        ConfigRepository $config
    ) {
        $this->roleRepository = $roleRepository;
        $this->permissionRepository = $permissionRepository;
        $this->userRepository = $userRepository;
        $this->config = $config;
    }

    /**
     * @param string|null $guardName
     */
    public function createAllPermissions($guardName = null)
    {
        $guardName = $guardName ?: $this->config->get('auth.defaults.guard');

        foreach (DomainPermission::values() as $permissionName) {
            $permission = new Permission;
            $permission->name = $permissionName;
            $permission->guard = $guardName;
            $this->permissionRepository->save($permission);
        }
    }

    public function attachPermissionToRole(Role $role, Permission $permission): void
    {
        $role->permissions()->attach($permission);
        $this->roleRepository->save($role);

        // Role이 Permission 보다 상위 개념이므로, Role 할당은 Permission으로 Propagate됨.
        $users = $role->users;
        foreach ($users as $user) {
            $this->grantPermissionToUser($user, $permission);
            $this->userRepository->save($user);
        }
    }

    public function detachPermissionFromRole(Role $role, Permission $permission): void
    {
        $role->permissions()->detach($permission);
        $this->roleRepository->save($role);

        $users = $role->users;
        foreach ($users as $user) {
            $this->revokePermissionFromUser($user, $permission);
            $this->userRepository->save($user);
        }
    }

    /**
     * @param Role $role
     * @param array|Permission[]|Collection $permissions
     */
    public function syncPermissionsOfRole(Role $role, $permissions): void
    {
        $permissionIds = $this->getPermissionIds($permissions);
        $role->permissions()->sync($permissionIds);
        $this->roleRepository->save($role);

        $users = $role->users;
        foreach ($users as $user) {
            $this->syncPermissionsOfUser($user, $permissions);
            $this->userRepository->save($user);
        }
    }

    // DESIGN NOTE.
    // 1. User에게 다수의 Role을 Assign 할 수 있다.
    // 2. User에게 Role을 통해서만 Permission을 give/revoke할 수 있다.
    // 3. 그럼에도 불구하고, 조회 편의를 위해 User-Permission간의 Many-to-many 관계를 유지하고,
    //    givePermissionToRole/revokePermissionToRole/syncPermissionOfRole API를 호출하면,
    //    User-Permission 맵핑 저장소도 User-Role-Permission 관계와 일치하도록 업데이트한다.

    public function grantPermissionToUser(User $user, Permission $permission): void
    {
        // Role이 Permission 보다 상위 개념이므로, Permission 할당이 Role로 Bubble up 되지 않음.
        $user->permissions()->attach($permission);
    }

    public function revokePermissionFromUser(User $user, Permission $permission): void
    {
        $user->permissions()->detach($permission);
    }

    /**
     * @param User $user
     * @param array|Permission[]|Collection $permissions
     */
    public function syncPermissionsOfUser(User $user, $permissions): void
    {
        $permissionIds = $this->getPermissionIds($permissions);
        $user->permissions()->sync($permissionIds);
    }

    /**
     * @param array|Permission[]|Collection $permissions
     * @return array|int[]
     */
    private function getPermissionIds($permissions)
    {
        return ($permissions instanceof Collection)
            ? $permissions->pluck('id')->toArray()
            : array_pluck($permissions, 'id');
    }
}