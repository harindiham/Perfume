<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Perfume;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;
use Illuminate\Support\Facades\Http;

class PerfumeApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthenticated_user_cannot_view_perfumes_api(): void
    {
        $response = $this->getJson('/api/perfumes');

        $response->assertUnauthorized();
    }

    public function test_customer_can_view_perfumes_with_read_ability(): void
    {
        $category = Category::create([
            'name' => 'Unisex',
            'description' => 'Unisex perfumes',
        ]);

        Perfume::create([
            'category_id' => $category->id,
            'name' => 'Test Perfume',
            'brand' => 'Test Brand',
            'price' => 15000,
            'size' => '50ml',
            'description' => 'Test perfume description',
            'top_notes' => 'Citrus',
            'middle_notes' => 'Rose',
            'base_notes' => 'Musk',
            'stock' => 10,
            'is_active' => true,
        ]);

        Sanctum::actingAs(
            User::factory()->create(),
            ['perfumes:read']
        );

        $response = $this->getJson('/api/perfumes');

        $response->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonFragment([
                'name' => 'Test Perfume',
            ]);
    }

    public function test_customer_cannot_create_perfume_with_read_only_ability(): void
    {
        $category = Category::create([
            'name' => 'Designer Perfumes',
            'description' => 'Designer fragrances',
        ]);

        Sanctum::actingAs(
            User::factory()->create(),
            ['perfumes:read']
        );

        $response = $this->postJson('/api/perfumes', [
            'category_id' => $category->id,
            'name' => 'Customer Attempt',
            'brand' => 'Test Brand',
            'price' => 20000,
            'size' => '50ml',
            'description' => 'Test description',
            'stock' => 5,
            'is_active' => true,
        ]);

        $response->assertForbidden();

        $this->assertDatabaseMissing('perfumes', [
            'name' => 'Customer Attempt',
        ]);
    }

    public function test_admin_can_create_perfume_with_write_ability(): void
    {
        $category = Category::create([
            'name' => 'Arabic Perfumes',
            'description' => 'Arabic fragrances',
        ]);

        Sanctum::actingAs(
            User::factory()->create([
                'is_admin' => true,
            ]),
            ['perfumes:read', 'perfumes:write']
        );

        $response = $this->postJson('/api/perfumes', [
            'category_id' => $category->id,
            'name' => 'Admin Test Perfume',
            'brand' => 'Test Brand',
            'price' => 25000,
            'size' => '100ml',
            'description' => 'A test perfume created by an administrator.',
            'top_notes' => 'Bergamot',
            'middle_notes' => 'Jasmine',
            'base_notes' => 'Vanilla',
            'stock' => 20,
            'is_active' => true,
        ]);

        $response->assertCreated()
            ->assertJsonPath('success', true)
            ->assertJsonFragment([
                'name' => 'Admin Test Perfume',
            ]);

        $this->assertDatabaseHas('perfumes', [
            'name' => 'Admin Test Perfume',
            'brand' => 'Test Brand',
        ]);
    }

    public function test_invalid_perfume_data_is_rejected(): void
    {
        Sanctum::actingAs(
            User::factory()->create([
                'is_admin' => true,
            ]),
            ['perfumes:read', 'perfumes:write']
        );

        $response = $this->postJson('/api/perfumes', [
            'category_id' => 99999,
            'name' => '',
            'brand' => '',
            'price' => -500,
            'description' => '',
            'stock' => -10,
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors([
                'category_id',
                'name',
                'brand',
                'price',
                'description',
                'stock',
            ]);
    }

    public function test_categories_api_returns_categories(): void
    {
        Category::create([
            'name' => 'Men\'s',
            'description' => 'Men\'s perfumes',
        ]);

        Sanctum::actingAs(
            User::factory()->create(),
            ['perfumes:read']
        );

        $response = $this->getJson('/api/categories');

        $response->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonFragment([
                'name' => 'Men\'s',
            ]);
    }

    public function test_category_api_returns_its_perfumes(): void
    {
        $category = Category::create([
            'name' => 'Women\'s',
            'description' => 'Women\'s perfumes',
        ]);

        Perfume::create([
            'category_id' => $category->id,
            'name' => 'Category Test Perfume',
            'brand' => 'Test Brand',
            'price' => 18000,
            'size' => '50ml',
            'description' => 'Test description',
            'stock' => 5,
            'is_active' => true,
        ]);

        Sanctum::actingAs(
            User::factory()->create(),
            ['perfumes:read']
        );

        $response = $this->getJson(
            "/api/categories/{$category->id}/perfumes"
        );

        $response->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonFragment([
                'name' => 'Category Test Perfume',
            ]);
    }

    public function test_perfumes_api_supports_pagination(): void
    {
        $category = Category::create([
            'name' => 'Pagination Category',
            'description' => 'Pagination test category',
        ]);

        for ($i = 1; $i <= 5; $i++) {
            Perfume::create([
                'category_id' => $category->id,
                'name' => "Pagination Perfume {$i}",
                'brand' => 'Test Brand',
                'price' => 10000 + ($i * 1000),
                'size' => '50ml',
                'description' => 'Pagination test perfume',
                'stock' => 10,
                'is_active' => true,
            ]);
        }

        Sanctum::actingAs(
            User::factory()->create(),
            ['perfumes:read']
        );

        $response = $this->getJson('/api/perfumes?per_page=2');

        $response->assertOk()
            ->assertJsonPath('pagination.per_page', 2)
            ->assertJsonPath('pagination.total', 5)
            ->assertJsonPath('pagination.current_page', 1)
            ->assertJsonCount(2, 'data');
    }

    public function test_perfumes_api_supports_search(): void
    {
        $category = Category::create([
            'name' => 'Search Category',
            'description' => 'Search test category',
        ]);

        Perfume::create([
            'category_id' => $category->id,
            'name' => 'Rose Garden',
            'brand' => 'Floral Brand',
            'price' => 20000,
            'size' => '50ml',
            'description' => 'Rose perfume',
            'stock' => 10,
            'is_active' => true,
        ]);

        Perfume::create([
            'category_id' => $category->id,
            'name' => 'Ocean Breeze',
            'brand' => 'Fresh Brand',
            'price' => 18000,
            'size' => '50ml',
            'description' => 'Fresh perfume',
            'stock' => 10,
            'is_active' => true,
        ]);

        Sanctum::actingAs(
            User::factory()->create(),
            ['perfumes:read']
        );

        $response = $this->getJson('/api/perfumes?search=Rose');

        $response->assertOk()
            ->assertJsonFragment([
                'name' => 'Rose Garden',
            ])
            ->assertJsonMissing([
                'name' => 'Ocean Breeze',
            ]);
    }

    public function test_perfumes_api_supports_category_filtering(): void
    {
        $categoryOne = Category::create([
            'name' => 'Category One',
            'description' => 'First category',
        ]);

        $categoryTwo = Category::create([
            'name' => 'Category Two',
            'description' => 'Second category',
        ]);

        Perfume::create([
            'category_id' => $categoryOne->id,
            'name' => 'Category One Perfume',
            'brand' => 'Brand One',
            'price' => 20000,
            'description' => 'Test perfume',
            'stock' => 10,
            'is_active' => true,
        ]);

        Perfume::create([
            'category_id' => $categoryTwo->id,
            'name' => 'Category Two Perfume',
            'brand' => 'Brand Two',
            'price' => 25000,
            'description' => 'Test perfume',
            'stock' => 10,
            'is_active' => true,
        ]);

        Sanctum::actingAs(
            User::factory()->create(),
            ['perfumes:read']
        );

        $response = $this->getJson(
            "/api/perfumes?category_id={$categoryOne->id}"
        );

        $response->assertOk()
            ->assertJsonFragment([
                'name' => 'Category One Perfume',
            ])
            ->assertJsonMissing([
                'name' => 'Category Two Perfume',
            ]);
    }

    public function test_perfumes_api_supports_price_filtering(): void
    {
        $category = Category::create([
            'name' => 'Price Category',
            'description' => 'Price test category',
        ]);

        Perfume::create([
            'category_id' => $category->id,
            'name' => 'Budget Perfume',
            'brand' => 'Brand One',
            'price' => 10000,
            'description' => 'Budget perfume',
            'stock' => 10,
            'is_active' => true,
        ]);

        Perfume::create([
            'category_id' => $category->id,
            'name' => 'Premium Perfume',
            'brand' => 'Brand Two',
            'price' => 50000,
            'description' => 'Premium perfume',
            'stock' => 10,
            'is_active' => true,
        ]);

        Sanctum::actingAs(
            User::factory()->create(),
            ['perfumes:read']
        );

        $response = $this->getJson(
            '/api/perfumes?min_price=40000&max_price=60000'
        );

        $response->assertOk()
            ->assertJsonFragment([
                'name' => 'Premium Perfume',
            ])
            ->assertJsonMissing([
                'name' => 'Budget Perfume',
            ]);
    }

    public function test_perfumes_api_supports_sorting_by_price(): void
    {
        $category = Category::create([
            'name' => 'Sorting Category',
            'description' => 'Sorting test category',
        ]);

        Perfume::create([
            'category_id' => $category->id,
            'name' => 'Expensive Perfume',
            'brand' => 'Brand One',
            'price' => 50000,
            'description' => 'Expensive perfume',
            'stock' => 10,
            'is_active' => true,
        ]);

        Perfume::create([
            'category_id' => $category->id,
            'name' => 'Cheap Perfume',
            'brand' => 'Brand Two',
            'price' => 10000,
            'description' => 'Cheap perfume',
            'stock' => 10,
            'is_active' => true,
        ]);

        Sanctum::actingAs(
            User::factory()->create(),
            ['perfumes:read']
        );

        $response = $this->getJson(
            '/api/perfumes?sort_by=price&sort_direction=asc'
        );

        $response->assertOk()
            ->assertJsonPath('data.0.name', 'Cheap Perfume')
            ->assertJsonPath('data.1.name', 'Expensive Perfume');
    }

    public function test_perfumes_api_rejects_invalid_query_parameters(): void
    {
        Sanctum::actingAs(
            User::factory()->create(),
            ['perfumes:read']
        );

        $response = $this->getJson(
            '/api/perfumes?sort_by=password&sort_direction=random&per_page=100'
        );

        $response->assertUnprocessable()
            ->assertJsonValidationErrors([
                'sort_by',
                'sort_direction',
                'per_page',
            ]);
    }

    public function test_perfumes_api_rejects_invalid_price_range(): void
    {
        Sanctum::actingAs(
            User::factory()->create(),
            ['perfumes:read']
        );

        $response = $this->getJson(
            '/api/perfumes?min_price=50000&max_price=10000'
        );

        $response->assertUnprocessable()
            ->assertJsonValidationErrors([
                'max_price',
            ]);
    }

    public function test_logout_revokes_the_current_api_token(): void
{
    $user = User::factory()->create();

    $newToken = $user->createToken(
        'test-token',
        ['perfumes:read']
    );

    $token = $newToken->plainTextToken;
    $tokenId = $newToken->accessToken->id;

    $this->withToken($token)
        ->postJson('/api/logout')
        ->assertOk();

    $this->assertDatabaseMissing('personal_access_tokens', [
        'id' => $tokenId,
    ]);
}

public function test_exchange_rate_api_returns_external_rate(): void
{
    Http::fake([
        'api.frankfurter.dev/*' => Http::response([
            'date' => '2026-10-02',
            'base' => 'LKR',
            'quote' => 'USD',
            'rate' => 0.00302,
        ], 200),
    ]);

    $user = User::factory()->create();

    $token = $user->createToken(
        'test-token',
        ['perfumes:read']
    )->plainTextToken;

    $response = $this->withToken($token)
        ->getJson('/api/exchange-rate?from=LKR&to=USD');

    $response
        ->assertOk()
        ->assertJson([
            'success' => true,
            'from' => 'LKR',
            'to' => 'USD',
            'rate' => 0.00302,
        ]);

    Http::assertSent(function ($request) {
        return str_contains($request->url(), 'api.frankfurter.dev/v2/rate/LKR/USD');
    });
}


public function test_exchange_rate_api_handles_external_api_failure(): void
{
    Http::fake([
        'api.frankfurter.dev/*' => Http::response([], 500),
    ]);

    $user = User::factory()->create();

    $token = $user->createToken(
        'test-token',
        ['perfumes:read']
    )->plainTextToken;

    $response = $this->withToken($token)
        ->getJson('/api/exchange-rate?from=LKR&to=USD');

    $response
        ->assertStatus(502)
        ->assertJson([
            'success' => false,
            'message' => 'Unable to retrieve exchange rate.',
        ]);
}


public function test_exchange_rate_api_validates_currency_codes(): void
{
    $user = User::factory()->create();

    $token = $user->createToken(
        'test-token',
        ['perfumes:read']
    )->plainTextToken;

    $response = $this->withToken($token)
        ->getJson('/api/exchange-rate?from=LK&to=USD');

    $response->assertStatus(422);
}

}