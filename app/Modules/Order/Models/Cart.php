<?php

namespace App\Modules\Order\Models;

use App\Modules\Order\Database\Factories\CartFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cart extends Model
{
    /** @use HasFactory<CartFactory> */
    use HasFactory;

    public const STATUS_ACTIVE = 'active';

    public const STATUS_CONVERTED = 'converted';

    protected $fillable = ['customer_id', 'status'];

    public function items(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function subtotal(): float
    {
        return round($this->items->sum(fn (CartItem $item) => $item->unit_price * $item->qty), 2);
    }

    protected static function newFactory(): CartFactory
    {
        return CartFactory::new();
    }
}
