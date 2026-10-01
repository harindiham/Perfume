<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Perfume;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

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
}