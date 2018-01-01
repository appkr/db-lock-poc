<?php

namespace TestSuite\Feature;

use Illuminate\Http\Response;

final class ProductApiTest extends FeatureTestHelper
{
    /** @test */
    public function unauthenticated_user_cannot_access_api()
    {
        $this->postJson('/api/v1/products', [], ['Authorization' => 'Bearer invalid_token'])
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /** @test */
    public function unauthorized_user_cannot_create_product()
    {
        $this->createUser();
        $this->loginAsUser();

        $this->postJson('/api/v1/products', [
            'title' => 'TEST TITLE',
            'stock' => 10,
            'price' => 1000,
            'description' => 'TEST DESCRIPTION',
        ], $this->authHeader)
            ->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /** @test */
    public function authorized_user_can_create_product()
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
    public function cannot_create_product_when_request_is_invalid()
    {
        $this->postJson('/api/v1/products', [], $this->authHeader)
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /** @test */
    public function even_visitors_can_see_list_of_products()
    {
        $this->postJson('/api/v1/products', [
            'title' => 'TEST TITLE',
            'stock' => 10,
            'price' => 1000,
            'description' => 'TEST DESCRIPTION',
        ], $this->authHeader);

        // 리뷰 목록 조회는 인증이 필요하지 않습니다.
        $this->createUser();
        $this->loginAsUser();
        $this->getJson('/api/v1/products?' . http_build_query([
                'q' => 'TITLE',
                'price_from' => 500,
                'price_to' => 2000
            ]),
            $this->authHeader
        )->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function authorized_user_can_update_product()
    {
        $responseBody = $this->postJson('/api/v1/products', [
            'title' => 'TEST TITLE',
            'stock' => 10,
            'price' => 1000,
            'description' => 'TEST DESCRIPTION',
        ], $this->authHeader)->decodeResponseJson();

        $productId = $responseBody['id'] ?? null;

        $modifiedResponseBody = $this->putJson("/api/v1/products/{$productId}", [
            'title' => 'MODIFIED',
        ], $this->authHeader)
            ->assertStatus(Response::HTTP_OK)
            ->decodeResponseJson();

        $this->assertEquals('MODIFIED', $modifiedResponseBody['title'] ?? null);
    }

    /** @test */
    public function authorized_user_can_delete_product()
    {
        $responseBody = $this->postJson('/api/v1/products', [
            'title' => 'TEST TITLE',
            'stock' => 10,
            'price' => 1000,
            'description' => 'TEST DESCRIPTION',
        ], $this->authHeader)->decodeResponseJson();

        $productId = $responseBody['id'] ?? null;

        $this->deleteJson("/api/v1/products/{$productId}", [], $this->authHeader)
            ->assertStatus(Response::HTTP_NO_CONTENT);
    }
}
