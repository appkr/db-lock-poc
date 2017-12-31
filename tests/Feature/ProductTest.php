<?php

namespace Tests\Feature;

use Illuminate\Http\Response;

final class ProductTest extends FeatureTestHelper
{
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
            ->assertStatus(Response::HTTP_CREATED);
    }

    /** @test */
    public function unprocessable_when_request_is_invalid()
    {
        $this->postJson('/api/v1/products', [], $this->authHeader)
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
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
        )->assertStatus(Response::HTTP_OK);
    }
}
