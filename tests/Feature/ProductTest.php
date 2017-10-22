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

    public function setUp()
    {
        parent::setUp();

        $this->user = factory(User::class)->create([
            'name' => 'John',
            'email' => 'john@foo.com',
        ]);
        $this->authHeader = [
            'Authorization' => 'Basic ' . base64_encode('john@foo.com:secret')
        ];
    }

    /** @test */
    public function cannot_create_product_when_credential_not_match()
    {
        $this->postJson('/api/v1/products', [], ['Authorization' => 'Basic invalid_credential'])
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
            ])
        )->dump()->assertStatus(200);
    }
}
