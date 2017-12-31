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
    const USER_CREDENTIAL = [
        'name' => 'User',
        'email' => 'user@example.com',
        'password' => 'secret',
    ];

    /** @var User $user */
    protected $user;
    /** @var string $authHeader */
    protected $authHeader;

    public function setUp()
    {
        parent::setUp();
        $this->seedRolesAndPermissions();
        $memberRole = App::make(RoleRepository::class)->findByName(DomainRole::MEMBER());
        $this->createUser([], [$memberRole]);
        $this->attemptLogin();
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
        $attributes = array_merge(self::USER_CREDENTIAL, $overrides);

        if (isset($attributes['password'])) {
            $attributes['password'] = bcrypt($attributes['password']);
        }

        $this->user = factory(User::class)->create($attributes);

        foreach ($roles as $role) {
            App::make(RoleService::class)->assignRoleToUser($this->user, $role);
        }

        if ($permissions) {
            $permissionService = App::make(PermissionService::class);
            foreach ($permissions as $permission) {
                $permissionService->grantPermissionToUser($this->user, $permission);
            }
        }
    }

    /**
     * @param array $overrides {
     *     @var string $name
     *     @var string $email
     *     @var string $password
     * }
     */
    protected function attemptLogin(array $overrides = [])
    {
        $credentials = array_merge(self::USER_CREDENTIAL, $overrides);
        $accessToken = $this->post(self::LOGIN_PATH, $credentials)
            ->decodeResponseJson()['access_token'];

        $this->authHeader = [
            'Authorization' => "Bearer {$accessToken}",
        ];
    }
}