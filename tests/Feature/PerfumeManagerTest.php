<?php

namespace Tests\Feature;

use App\Livewire\Admin\PerfumeManager;
use App\Models\Category;
use App\Models\Perfume;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class PerfumeManagerTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_a_perfume(): void
    {
        $admin = User::factory()->create([
            'is_admin' => true,
        ]);

        $category = Category::create([
            'name' => 'Unisex',
            'description' => 'Unisex perfumes',
        ]);

        $this->actingAs($admin);

        Livewire::test(PerfumeManager::class)
            ->call('create')
            ->set('name', 'Livewire Test Perfume')
            ->set('brand', 'Test Brand')
            ->set('category_id', $category->id)
            ->set('price', 15000)
            ->set('size', '50ml')
            ->set('description', 'Test perfume')
            ->set('top_notes', 'Bergamot')
            ->set('middle_notes', 'Jasmine')
            ->set('base_notes', 'Musk')
            ->set('stock', 10)
            ->set('is_active', true)
            ->call('save');

        $this->assertDatabaseHas('perfumes', [
            'name' => 'Livewire Test Perfume',
            'brand' => 'Test Brand',
        ]);
    }

    public function test_admin_can_edit_a_perfume(): void
    {
        $admin = User::factory()->create([
            'is_admin' => true,
        ]);

        $category = Category::create([
            'name' => 'Men\'s',
            'description' => 'Men\'s perfumes',
        ]);

        $perfume = Perfume::create([
            'category_id' => $category->id,
            'name' => 'Original Name',
            'brand' => 'Original Brand',
            'price' => 10000,
            'size' => '50ml',
            'description' => 'Original description',
            'stock' => 10,
            'is_active' => true,
        ]);

        $this->actingAs($admin);

        Livewire::test(PerfumeManager::class)
            ->call('edit', $perfume->id)
            ->set('name', 'Updated Name')
            ->set('brand', 'Updated Brand')
            ->call('save');

        $this->assertDatabaseHas('perfumes', [
            'id' => $perfume->id,
            'name' => 'Updated Name',
            'brand' => 'Updated Brand',
        ]);
    }

    public function test_admin_can_delete_a_perfume(): void
    {
        $admin = User::factory()->create([
            'is_admin' => true,
        ]);

        $category = Category::create([
            'name' => 'Arabic Perfumes',
            'description' => 'Arabic fragrances',
        ]);

        $perfume = Perfume::create([
            'category_id' => $category->id,
            'name' => 'Delete Test Perfume',
            'brand' => 'Test Brand',
            'price' => 12000,
            'size' => '50ml',
            'description' => 'Test description',
            'stock' => 5,
            'is_active' => true,
        ]);

        $this->actingAs($admin);

        Livewire::test(PerfumeManager::class)
            ->call('delete', $perfume->id);

        $this->assertDatabaseMissing('perfumes', [
            'id' => $perfume->id,
        ]);
    }

    public function test_perfume_creation_requires_required_fields(): void
    {
        $admin = User::factory()->create([
            'is_admin' => true,
        ]);

        $this->actingAs($admin);

        Livewire::test(PerfumeManager::class)
            ->call('create')
            ->call('save')
            ->assertHasErrors([
                'name',
                'brand',
                'category_id',
                'price',
            ]);
    }
}