<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
    ];

    public function perfumes(): HasMany
    {
        return $this->hasMany(Perfume::class);
    }

    #[Scope]
    protected function withActivePerfumes(Builder $query): void
    {
        $query->whereHas('perfumes', function (Builder $perfumeQuery) {
            $perfumeQuery->where('is_active', true);
        });
    }
}