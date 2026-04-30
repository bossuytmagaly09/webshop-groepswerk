<?php

namespace App\Models;

use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'user_id', 'total_price', 'status',
    'email', 'shipping_first_name', 'shipping_last_name',
    'shipping_address_line_1', 'shipping_address_line_2',
    'shipping_postcode', 'shipping_city', 'shipping_country',
    'shipping_phone', 'checked_out_at',
    'stripe_session_id', 'stripe_payment_intent_id',
])]
class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected function casts(): array
    {
        return [
            'total_price' => 'decimal:2',
            'checked_out_at' => 'datetime',
            'status' => OrderStatus::class,
        ];
    }

    protected function formattedTotal(): Attribute
    {
        return Attribute::get(fn () => '€'.number_format((float) $this->total_price, 2, ',', '.'));
    }

    public function scopeSearch(Builder $query, string $search): void
    {
        $query->where(function (Builder $q) use ($search) {
            $q->where('id', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
                ->orWhere('shipping_first_name', 'like', "%{$search}%")
                ->orWhere('shipping_last_name', 'like', "%{$search}%")
                ->orWhereHas('user', function (Builder $u) use ($search) {
                    $u->where('name', 'like', "%{$search}%");
                });
        });
    }

    public function scopeWithStatus(Builder $query, OrderStatus $status): void
    {
        $query->where('status', $status->value);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
