<?php

namespace TestSuite\Feature;

use App\ApplicationContext;
use Illuminate\Http\Response;
use TestSuite\TestCase;

final class AuthApiTest extends TestCase
{
    const LOGIN_PATH = 'api/auth/login';
    const PROFILE_PATH = 'api/auth/me';

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
        $this->login([
            'email' => 'stranger@example.com',
            'password' => 'secret',
        ]);

        // 로그인할 때는 PHPUnit이 부트시킨 ApplicationContext를 사용했지만 (User.name='CLI', allowed_ips=['*']),
        // 프로필을 조회할 때 의도한 사용자로 바꾸어줍니다 (User.name='Stranger', allowed_ips=['10.10.10.10/32'])
        $currentContext = $this->app->make(ApplicationContext::class);
        $currentContext->setUser($this->tester);
        $responseBody = $this->post(self::PROFILE_PATH, [], $this->authHeader)->decodeResponseJson();

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
        $responseBody = $this->post(self::LOGIN_PATH, $credentials)->decodeResponseJson();
        $accessToken = $responseBody['access_token'] ?? null;
        $this->authHeader = [
            'Authorization' => "Bearer {$accessToken}",
        ];
    }
}
