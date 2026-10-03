<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Perfume;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EloquentRelationshipTest extends TestCase
{
    use RefreshDatabase;

    public function test_category_has_many_perfumes(): void
    {
        $category = Category::create([
            'name' => 'Unisex',
            'description' => 'Unisex perfumes',
        ]);

        $perfume1 = Perfume::create([
            'category_id' => $category->id,
            'name' => 'Test Perfume One',
            'brand' => 'Test Brand',
            'price' => 15000,
            'size' => '50ml',
            'description' => 'Test perfume',
            'stock' => 10,
            'is_active' => true,
        ]);

        $perfume2 = Perfume::create([
            'category_id' => $category->id,
            'name' => 'Test Perfume Two',
            'brand' => 'Another Brand',
            'price' => 18000,
            'size' => '100ml',
            'description' => 'Another test perfume',
            'stock' => 5,
            'is_active' => true,
        ]);

        $this->assertCount(2, $category->perfumes);

        $this->assertTrue(
            $category->perfumes->contains($perfume1)
        );

        $this->assertTrue(
            $category->perfumes->contains($perfume2)
        );
    }

    public function test_perfume_belongs_to_category(): void
    {
        $category = Category::create([
            'name' => 'Designer Perfumes',
            'description' => 'Designer fragrances',
        ]);

        $perfume = Perfume::create([
            'category_id' => $category->id,
            'name' => 'Test Designer Perfume',
            'brand' => 'Test Brand',
            'price' => 25000,
            'size' => '100ml',
            'description' => 'Designer perfume',
            'stock' => 10,
            'is_active' => true,
        ]);

        $this->assertTrue(
            $perfume->category->is($category)
        );
    }
}