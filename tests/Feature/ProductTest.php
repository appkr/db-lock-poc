<?php

namespace Tests\Feature;

use Myshop\Domain\Model\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

/**
 * @property mixed user
 * @property string authHeader
 */
class ProductTest extends TestCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;

    const LOGIN_PATH = 'api/auth/login';
    const USER_CREDENTIAL = [
        'name' => 'John',
        'email' => 'john@foo.com',
        'password' => 'password',
    ];

    public function setUp()
    {
        parent::setUp();
        $this->createUser();
        $this->attemptLogin();
    }

        private function createUser(array $overrides = [])
        {
            $attributes = array_merge(self::USER_CREDENTIAL, $overrides);

            if (isset($attributes['password'])) {
                $attributes['password'] = bcrypt($attributes['password']);
            }

            $this->user = factory(User::class)->create($attributes);
        }

        private function attemptLogin(array $overrides = [])
        {
            $credentials = array_merge(self::USER_CREDENTIAL, $overrides);
            $accessToken = $this->post(self::LOGIN_PATH, $credentials)
                ->decodeResponseJson()['access_token'];

            $this->authHeader = [
                'Authorization' => "Bearer {$accessToken}",
            ];
        }

    /** @test */
    public function cannot_create_product_when_credential_not_match()
    {
        $this->postJson('/api/v1/products', [], ['Authorization' => 'Bearer invalid_credential'])
            ->assertStatus(401);
    }

    /** @test */
    public function can_create_product()
    {
        $this->postJson('/api/v1/products', [
                'title' => 'TEST TITLE',
                'stock' => 10,
                'price' => 1000,
                'description' => 'TEST DESCRIPTION',
            ], $this->authHeader)
            ->assertStatus(200);
    }

    /** @test */
    public function unprocessable_when_request_is_invalid()
    {
        $this->postJson('/api/v1/products', [], $this->authHeader)
            ->assertStatus(422);
    }

    /** @test */
    public function can_see_list_of_products()
    {
        $this->postJson('/api/v1/products', [
            'title' => 'TEST TITLE',
            'stock' => 10,
            'price' => 1000,
            'description' => 'TEST DESCRIPTION',
        ], $this->authHeader);

        $this->getJson('/api/v1/products?' . http_build_query([
                'q' => 'TITLE',
                'price_from' => 500,
                'price_to' => 2000
            ]),
            $this->authHeader
        )->assertStatus(200);
    }
}
