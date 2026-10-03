<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Perfume;

class PerfumeController extends Controller
{
    public function index()
    {
        $perfumes = Perfume::with('category')
            ->active()
            ->latest()
            ->get();

        $categories = Category::withCount([
            'perfumes' => function ($query) {
                $query->where('is_active', true);
            }
        ])
        ->orderBy('name')
        ->get();

        return view('perfumes.index', [
            'perfumes' => $perfumes,
            'categories' => $categories,
        ]);
    }


    public function category($id)
    {
        $category = Category::findOrFail($id);

        $perfumes = Perfume::with('category')
            ->active()
            ->byCategory($category->id)
            ->latest()
            ->get();

        $categories = Category::withCount([
            'perfumes' => function ($query) {
                $query->where('is_active', true);
            }
        ])
        ->orderBy('name')
        ->get();

        return view('perfumes.index', [
            'category' => $category,
            'perfumes' => $perfumes,
            'categories' => $categories,
        ]);
    }


    public function show($id)
    {
        $perfume = Perfume::with('category')
            ->active()
            ->findOrFail($id);

        return view('perfumes.show', [
            'perfume' => $perfume,
        ]);
    }
}