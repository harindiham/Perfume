<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Perfume;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PerfumeController extends Controller
{
    public function index()
    {
        $perfumes = Perfume::with('category')
            ->where('is_active', true)
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $perfumes,
        ]);
    }

    public function show(Perfume $perfume)
    {
        if (! $perfume->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Perfume not found.',
            ], 404);
        }

        $perfume->load('category');

        return response()->json([
            'success' => true,
            'data' => $perfume,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => [
                'required',
                'integer',
                Rule::exists('categories', 'id'),
            ],
            'name' => ['required', 'string', 'max:255'],
            'brand' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'size' => ['nullable', 'string', 'max:100'],
            'description' => ['required', 'string'],
            'top_notes' => ['nullable', 'string', 'max:1000'],
            'middle_notes' => ['nullable', 'string', 'max:1000'],
            'base_notes' => ['nullable', 'string', 'max:1000'],
            'image' => ['nullable', 'string', 'max:2048'],
            'stock' => ['required', 'integer', 'min:0'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $perfume = Perfume::create($validated);

        $perfume->load('category');

        return response()->json([
            'success' => true,
            'message' => 'Perfume created successfully.',
            'data' => $perfume,
        ], 201);
    }
}