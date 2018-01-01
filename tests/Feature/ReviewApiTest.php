<?php

namespace TestSuite\Feature;

use Illuminate\Http\Response;

final class ReviewApiTest extends FeatureTestHelper
{
    /** @var int $productId */
    private $productId;

    public function setUp()
    {
        parent::setUp();
        $this->createProduct();
        $this->createUser();
        $this->loginAsUser();
    }

    /** @test */
    public function authenticated_user_can_create_review()
    {
        $this->postJson("/api/v1/products/{$this->productId}/reviews", [
            'title' => 'TEST TITLE',
            'content' => 'TEST CONTENT',
        ], $this->authHeader)
            ->assertStatus(Response::HTTP_CREATED);
    }

    /** @test */
    public function unprocessable_when_request_is_invalid()
    {
        $this->postJson("/api/v1/products/{$this->productId}/reviews",
            [], $this->authHeader)
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /** @test */
    public function even_visitors_can_see_list_of_reviews()
    {
        // 리뷰 목록 조회는 인증이 필요하지 않습니다.
        $this->getJson("/api/v1/products/{$this->productId}/reviews", $this->authHeader)
            ->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function unauthorized_cannot_update_review()
    {
        $responseBody = $this->postJson("/api/v1/products/{$this->productId}/reviews", [
            'title' => 'TEST TITLE',
            'content' => 'TEST CONTENT',
        ], $this->authHeader)->decodeResponseJson();

        // 리뷰 작성자 또는 DomainPermission::MANAGE_REVIEW() 권한을 가진 사용자만 리뷰를 수정할 수 있습니다.
        $this->createUser(['email' => 'just-reader@example.com']);
        $this->login(['email' => 'just-reader@example.com']);
        $reviewId = $responseBody['id'] ?? null;

        $this->putJson("/api/v1/products/{$this->productId}/reviews/{$reviewId}", [
                'title' => 'MODIFIED',
        ], $this->authHeader)
            ->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /** @test */
    public function authorized_can_update_review()
    {
        $responseBody = $this->postJson("/api/v1/products/{$this->productId}/reviews", [
            'title' => 'TEST TITLE',
            'content' => 'TEST CONTENT',
        ], $this->authHeader)->decodeResponseJson();

        $reviewId = $responseBody['id'] ?? null;

        $modifiedResponseBody = $this->putJson("/api/v1/products/{$this->productId}/reviews/{$reviewId}", [
            'title' => 'MODIFIED',
        ], $this->authHeader)
            ->assertStatus(Response::HTTP_OK)
            ->decodeResponseJson();

        $this->assertEquals('MODIFIED', $modifiedResponseBody['title'] ?? null);
    }

    /** @test */
    public function authorized_can_delete_product()
    {
        $responseBody = $this->postJson("/api/v1/products/{$this->productId}/reviews", [
            'title' => 'TEST TITLE',
            'content' => 'TEST CONTENT',
        ], $this->authHeader)->decodeResponseJson();

        $reviewId = $responseBody['id'] ?? null;

        $this->deleteJson("/api/v1/products/{$this->productId}/reviews/{$reviewId}", [], $this->authHeader)
            ->assertStatus(Response::HTTP_NO_CONTENT);
    }

    private function createProduct()
    {
        $this->productId = $this->postJson('/api/v1/products', [
            'title' => 'TEST TITLE',
            'stock' => 10,
            'price' => 1000,
            'description' => 'TEST DESCRIPTION',
        ], $this->authHeader)->decodeResponseJson()['id'];
    }
}
