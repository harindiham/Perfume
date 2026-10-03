<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Perfume;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PerfumeController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'category_id' => [
                'nullable',
                'integer',
                Rule::exists('categories', 'id'),
            ],
            'min_price' => ['nullable', 'numeric', 'min:0'],
            'max_price' => ['nullable', 'numeric', 'min:0', 'gte:min_price'],
            'sort_by' => [
                'nullable',
                'string',
                Rule::in(['name', 'price', 'created_at']),
            ],
            'sort_direction' => [
                'nullable',
                'string',
                Rule::in(['asc', 'desc']),
            ],
            'per_page' => [
                'nullable',
                'integer',
                'min:1',
                'max:50',
            ],
        ]);

        $query = Perfume::with('category')
    ->active();

        // Search by perfume name, brand, or category name.
        if (!empty($validated['search'])) {

            $search = $validated['search'];

            $query->where(function ($query) use ($search) {

                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('brand', 'like', "%{$search}%")
                    ->orWhereHas('category', function ($categoryQuery) use ($search) {
                        $categoryQuery->where(
                            'name',
                            'like',
                            "%{$search}%"
                        );
                    });

            });
        }

        // Filter by category.
        if (isset($validated['category_id'])) {

            $query->where(
                'category_id',
                $validated['category_id']
            );
        }

        // Filter by minimum price.
        if (isset($validated['min_price'])) {

            $query->where(
                'price',
                '>=',
                $validated['min_price']
            );
        }

        // Filter by maximum price.
        if (isset($validated['max_price'])) {

            $query->where(
                'price',
                '<=',
                $validated['max_price']
            );
        }

        // Only allow approved database columns for sorting.
        $sortBy = $validated['sort_by'] ?? 'created_at';
        $sortDirection = $validated['sort_direction'] ?? 'desc';

        $query->orderBy($sortBy, $sortDirection);

        // Prevent excessively large API responses.
        $perPage = $validated['per_page'] ?? 10;

        $perfumes = $query
            ->paginate($perPage)
            ->withQueryString();

        return response()->json([
            'success' => true,

            'data' => $perfumes->items(),

            'pagination' => [
                'current_page' => $perfumes->currentPage(),
                'last_page' => $perfumes->lastPage(),
                'per_page' => $perfumes->perPage(),
                'total' => $perfumes->total(),
                'from' => $perfumes->firstItem(),
                'to' => $perfumes->lastItem(),
                'has_more_pages' => $perfumes->hasMorePages(),
            ],
        ]);
    }


    public function show(Perfume $perfume)
    {
        if (!$perfume->is_active) {

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

            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'brand' => [
                'required',
                'string',
                'max:255',
            ],

            'price' => [
                'required',
                'numeric',
                'min:0',
            ],

            'size' => [
                'nullable',
                'string',
                'max:100',
            ],

            'description' => [
                'required',
                'string',
            ],

            'top_notes' => [
                'nullable',
                'string',
                'max:1000',
            ],

            'middle_notes' => [
                'nullable',
                'string',
                'max:1000',
            ],

            'base_notes' => [
                'nullable',
                'string',
                'max:1000',
            ],

            'image' => [
                'nullable',
                'string',
                'max:2048',
            ],

            'stock' => [
                'required',
                'integer',
                'min:0',
            ],

            'is_active' => [
                'sometimes',
                'boolean',
            ],
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