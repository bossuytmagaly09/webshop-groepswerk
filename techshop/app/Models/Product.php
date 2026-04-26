<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['category_id', 'name', 'slug', 'description', 'price', 'stock', 'image'])]
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
        $query->when($category !== '', function (Builder $q) use ($category) {
            if (ctype_digit($category)) {
                $q->where('category_id', (int) $category);

                return;
            }

            $q->whereHas('category', fn (Builder $cq) => $cq->where('slug', $category));
        });
    }

    public function scopeNewest(Builder $query, int $limit = 4): void
    {
        $query->latest('created_at')->latest('id')->limit($limit);
    }

    protected function formattedPrice(): Attribute
    {
        return Attribute::get(fn (): string => '€ '.number_format((float) $this->price, 2, ',', '.'));
    }
}
