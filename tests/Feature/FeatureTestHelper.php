<?php

namespace TestSuite\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use TestSuite\TestCase;

class FeatureTestHelper extends TestCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;

    const LOGIN_PATH = 'api/auth/login';

    /** @var string $authHeader */
    protected $authHeader;

    public function setUp()
    {
        parent::setUp();
        $this->seedRolesAndPermissions();
        $this->createMember();
        $this->loginAsMember();
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