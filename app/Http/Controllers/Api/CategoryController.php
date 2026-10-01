<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount([
            'perfumes' => function ($query) {
                $query->where('is_active', true);
            }
        ])
        ->orderBy('name')
        ->get();

        return response()->json([
            'success' => true,
            'data' => $categories,
        ]);
    }

    public function perfumes(Category $category)
    {
        $perfumes = $category->perfumes()
            ->where('is_active', true)
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'category' => $category,
            'data' => $perfumes,
        ]);
    }
}