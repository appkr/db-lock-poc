<?php

namespace TestSuite;

use App;
use App\Exceptions\Handler;
use Exception;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Myshop\Application\Service\PermissionService;
use Myshop\Application\Service\RoleService;
use Myshop\Common\Model\DomainPermission;
use Myshop\Common\Model\DomainRole;
use Myshop\Domain\Model\Permission;
use Myshop\Domain\Model\Role;
use Myshop\Domain\Model\User;
use Myshop\Domain\Repository\RoleRepository;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use DatabaseMigrations;
    use DatabaseTransactions;

    /** @var User $tester */
    protected $tester;

    /**
     * {@inheritdoc}
     */
    public function expectException($e)
    {
        $this->disableExceptionHandling();
        parent::expectException($e);

        return $this;
    }

    protected function disableExceptionHandling()
    {
        $this->app->instance(Handler::class, new class extends Handler {
            public function __construct()
            {
            }

            public function report(Exception $e)
            {
            }

            public function render($request, Exception $e)
            {
                throw $e;
            }
        });

        return $this;
    }

    protected function seedRolesAndPermissions()
    {
        App::make(PermissionService::class)->createAllPermissions();
        $roleService = App::make(RoleService::class);
        $roleService->createRole(DomainRole::ADMIN(), [DomainPermission::MANAGE_USER()]);
        $roleService->createRole(DomainRole::MEMBER(), [
            DomainPermission::MANAGE_PRODUCT(),
            DomainPermission::MANAGE_REVIEW()
        ]);
        $roleService->createRole(DomainRole::USER());
    }

    /**
     * @param array $overrides {
     *     @var string $name
     *     @var string $email
     *     @var string $password
     *     @var array $allowed_ips
     * }
     * @param array|Role[] $roles
     * @param array|Permission[] $permissions
     */
    protected function createUser(
        array $overrides = [],
        array $roles = [],
        array $permissions = []
    )
    {
        $attributes = array_merge([
            'name' => 'User',
            'email' => 'user@example.com',
            'password' => 'secret',
            'allowed_ips' => ['*'],
        ], $overrides);

        if (isset($attributes['password'])) {
            $attributes['password'] = bcrypt($attributes['password']);
        }

        $this->tester = factory(User::class)->create($attributes);
        $userRole = App::make(RoleRepository::class)->findByName(DomainRole::USER());
        $roles = array_merge($roles, [$userRole]);

        foreach ($roles as $role) {
            App::make(RoleService::class)->assignRoleToUser($this->tester, $role);
        }

        if ($permissions) {
            $permissionService = App::make(PermissionService::class);
            foreach ($permissions as $permission) {
                $permissionService->grantPermissionToUser($this->tester, $permission);
            }
        }
    }

    /**
     * @param array|Permission[] $permissions
     */
    protected function createAdmin(
        array $permissions = []
    )
    {
        $attributes = [
            'name' => 'Admin',
            'email' => 'admin@example.com',
        ];
        $adminRole = App::make(RoleRepository::class)->findByName(DomainRole::ADMIN());

        $this->createUser($attributes, [$adminRole], $permissions);
    }

    /**
     * @param array|Permission[] $permissions
     */
    protected function createMember(
        array $permissions = []
    )
    {
        $attributes = [
            'name' => 'Member',
            'email' => 'member@example.com',
        ];
        $memberRole = App::make(RoleRepository::class)->findByName(DomainRole::MEMBER());

        $this->createUser($attributes, [$memberRole], $permissions);
    }
}
