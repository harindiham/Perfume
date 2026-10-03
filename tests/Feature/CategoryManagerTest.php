<?php

namespace Tests\Feature;

use App\Livewire\Admin\CategoryManager;
use App\Models\Category;
use App\Models\Perfume;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class CategoryManagerTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_a_category(): void
    {
        $admin = User::factory()->create([
            'is_admin' => true,
        ]);

        $this->actingAs($admin);

        Livewire::test(CategoryManager::class)
            ->call('create')
            ->set('name', 'Test Category')
            ->set('description', 'Test category description')
            ->call('save');

        $this->assertDatabaseHas('categories', [
            'name' => 'Test Category',
            'description' => 'Test category description',
        ]);
    }

    public function test_admin_can_edit_a_category(): void
    {
        $admin = User::factory()->create([
            'is_admin' => true,
        ]);

        $category = Category::create([
            'name' => 'Original Category',
            'description' => 'Original description',
        ]);

        $this->actingAs($admin);

        Livewire::test(CategoryManager::class)
            ->call('edit', $category->id)
            ->set('name', 'Updated Category')
            ->set('description', 'Updated description')
            ->call('save');

        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'name' => 'Updated Category',
            'description' => 'Updated description',
        ]);
    }

    public function test_admin_can_delete_empty_category(): void
    {
        $admin = User::factory()->create([
            'is_admin' => true,
        ]);

        $category = Category::create([
            'name' => 'Delete Category',
            'description' => 'Category to delete',
        ]);

        $this->actingAs($admin);

        Livewire::test(CategoryManager::class)
            ->call('delete', $category->id);

        $this->assertDatabaseMissing('categories', [
            'id' => $category->id,
        ]);
    }

    public function test_category_with_perfumes_cannot_be_deleted(): void
    {
        $admin = User::factory()->create([
            'is_admin' => true,
        ]);

        $category = Category::create([
            'name' => 'Protected Category',
            'description' => 'Category containing perfumes',
        ]);

        Perfume::create([
            'category_id' => $category->id,
            'name' => 'Protected Perfume',
            'brand' => 'Test Brand',
            'price' => 15000,
            'size' => '50ml',
            'description' => 'Test perfume',
            'stock' => 10,
            'is_active' => true,
        ]);

        $this->actingAs($admin);

        Livewire::test(CategoryManager::class)
            ->call('delete', $category->id);

        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
        ]);
    }

    public function test_category_name_is_required(): void
    {
        $admin = User::factory()->create([
            'is_admin' => true,
        ]);

        $this->actingAs($admin);

        Livewire::test(CategoryManager::class)
            ->call('create')
            ->set('name', '')
            ->call('save')
            ->assertHasErrors([
                'name',
            ]);
    }
}