<?php

namespace TestSuite\Feature;

use App\Http\Exception\NotAllowedIpException;
use Illuminate\Http\Response;
use TestSuite\TestCase;

final class AuthApiTest extends TestCase
{
    const LOGIN_PATH = 'api/auth/login';

    /** @var string $authHeader */
    private $authHeader;

    public function setUp()
    {
        parent::setUp();
        $this->seedRolesAndPermissions();
    }

    /** @test */
    public function user_cannot_login_via_not_allowed_ips()
    {
        $this->createUser([
            'name' => 'Stranger',
            'email' => 'stranger@example.com',
            'password' => 'secret',
            'allowed_ips' => ['10.10.10.10/32'],
        ]);
        $responseBody = $this->post(self::LOGIN_PATH, [
            'email' => 'stranger@example.com',
            'password' => 'secret',
        ])->decodeResponseJson();

        $this->assertEquals(Response::HTTP_FORBIDDEN, $responseBody['code']);
    }

    /**
     * @param array $credentials {
     *     @var string $email
     *     @var string $password
     * }
     * @throws \Exception
     */
    private function login(array $credentials = [])
    {
        $responseBody = $this->post(self::LOGIN_PATH, $credentials)
            ->decodeResponseJson();
        $accessToken = $responseBody['access_token'] ?? null;

        $this->authHeader = [
            'Authorization' => "Bearer {$accessToken}",
        ];
    }
}
