<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Perfume extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'brand',
        'price',
        'size',
        'description',
        'top_notes',
        'middle_notes',
        'base_notes',
        'image',
        'stock',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    protected $appends = [
        'formatted_price',
        'stock_status',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    #[Scope]
    protected function active(Builder $query): void
    {
        $query->where('is_active', true);
    }

    #[Scope]
    protected function lowStock(Builder $query): void
    {
        $query->where('stock', '<=', 5);
    }

    #[Scope]
    protected function byCategory(Builder $query, int $categoryId): void
    {
        $query->where('category_id', $categoryId);
    }

    protected function formattedPrice(): Attribute
    {
        return Attribute::make(
            get: fn () => 'LKR ' . number_format((float) $this->price, 2),
        );
    }

    protected function stockStatus(): Attribute
    {
        return Attribute::make(
            get: function () {
                if ($this->stock <= 0) {
                    return 'Out of Stock';
                }

                if ($this->stock <= 5) {
                    return 'Low Stock';
                }

                return 'In Stock';
            },
        );
    }
}