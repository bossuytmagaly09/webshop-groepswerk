<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['category_id', 'name', 'slug', 'description', 'price', 'stock'])]
class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'stock' => 'integer',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function scopeAvailable(Builder $query): void
    {
        $query->where('stock', '>', 0);
    }

    public function scopeSearch(Builder $query, string $search): void
    {
        $query->when($search !== '', fn (Builder $q) => $q->where('name', 'like', "%{$search}%"));
    }

    public function scopeInCategory(Builder $query, string $category): void
    {
        $query->when($category !== '', fn (Builder $q) => $q->where('category_id', (int) $category));
    }
}
