<?php

namespace App\Modules\Order\Models;

use App\Modules\Order\Database\Factories\OrderFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    /** @use HasFactory<OrderFactory> */
    use HasFactory;

    public const STATUS_PENDING = 'pending';

    public const STATUS_PAID = 'paid';

    public const STATUS_COMPLETED = 'completed';

    public const STATUS_CANCELLED = 'cancelled';

    public const SHIPPING_FLAT = 20_000.0;

    public const FREE_SHIPPING_MIN = 500_000.0;

    protected $fillable = [
        'code',
        'customer_id',
        'address_id',
        'status',
        'subtotal',
        'shipping_cost',
        'total',
        'shipping_snapshot',
        'placed_at',
    ];

    protected function casts(): array
    {
        return [
            'subtotal' => 'decimal:2',
            'shipping_cost' => 'decimal:2',
            'total' => 'decimal:2',
            'shipping_snapshot' => 'array',
            'placed_at' => 'datetime',
        ];
    }

    public static function shippingFor(float $subtotal): float
    {
        return $subtotal >= self::FREE_SHIPPING_MIN ? 0.0 : self::SHIPPING_FLAT;
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function scopeOwnedBy(Builder $query, int $customerId): Builder
    {
        return $query->where('customer_id', $customerId);
    }

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            self::STATUS_PENDING => 'Menunggu pembayaran',
            self::STATUS_PAID => 'Dibayar',
            self::STATUS_COMPLETED => 'Selesai',
            self::STATUS_CANCELLED => 'Dibatalkan',
            default => ucfirst($this->status),
        };
    }

    public function statusBadge(): string
    {
        return match ($this->status) {
            self::STATUS_PENDING => 'warning',
            self::STATUS_PAID => 'info',
            self::STATUS_COMPLETED => 'success',
            self::STATUS_CANCELLED => 'secondary',
            default => 'light',
        };
    }

    protected static function newFactory(): OrderFactory
    {
        return OrderFactory::new();
    }
}
