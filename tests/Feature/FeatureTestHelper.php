<?php

namespace TestSuite\Feature;

use App;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Myshop\Application\Service\PermissionService;
use Myshop\Application\Service\RoleService;
use Myshop\Common\Model\DomainPermission;
use Myshop\Common\Model\DomainRole;
use Myshop\Domain\Model\User;
use Myshop\Domain\Repository\RoleRepository;
use TestSuite\TestCase;

class FeatureTestHelper extends TestCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;

    const LOGIN_PATH = 'api/auth/login';

    /** @var User $tester */
    protected $tester;
    /** @var string $authHeader */
    protected $authHeader;

    public function setUp()
    {
        parent::setUp();
        $this->seedRolesAndPermissions();
        $this->createMember();
        $this->loginAsMember();
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
     * }
     * @param array|Role[] $roles
     * @param array|Permission[] $permissions
     */
    protected function createUser(
        array $overrides = [],
        array $roles = [],
        array $permissions = []
    ) {
        $attributes = array_merge([
            'name' => 'User',
            'email' => 'user@example.com',
            'password' => 'secret',
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
    ) {
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
    ) {
        $attributes = [
            'name' => 'Member',
            'email' => 'member@example.com',
        ];
        $memberRole = App::make(RoleRepository::class)->findByName(DomainRole::MEMBER());

        $this->createUser($attributes, [$memberRole], $permissions);
    }

    /**
     * @param array $overrides {
     *     @var string $name
     *     @var string $email
     *     @var string $password
     * }
     */
    protected function login(array $overrides = [])
    {
        $credentials = array_merge([
            'email' => 'user@example.com',
            'password' => 'secret',
        ], $overrides);

        $accessToken = $this->post(self::LOGIN_PATH, $credentials)
            ->decodeResponseJson()['access_token'];

        $this->authHeader = [
            'Authorization' => "Bearer {$accessToken}",
        ];
    }

    protected function loginAsUser()
    {
        $this->login([
            'email' => 'user@example.com',
            'password' => 'secret',
        ]);
    }

    protected function loginAsMember()
    {
        $this->login([
            'email' => 'member@example.com',
            'password' => 'secret',
        ]);
    }

    protected function loginAsAdmin()
    {
        $this->login([
            'email' => 'admin@example.com',
            'password' => 'secret',
        ]);
    }
}