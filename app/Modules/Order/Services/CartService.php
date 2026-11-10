<?php

namespace App\Modules\Order\Services;

use App\Modules\Catalog\DTO\ProductSnapshot;
use App\Modules\Catalog\Exceptions\InsufficientStockException;
use App\Modules\Order\Models\Cart;
use App\Modules\Order\Models\CartItem;
use App\Modules\Order\Models\Order;

class CartService
{
    public function activeCartFor(int $customerId): Cart
    {
        $cart = Cart::firstOrCreate([
            'customer_id' => $customerId,
            'status' => Cart::STATUS_ACTIVE,
        ]);

        return $cart->loadMissing('items');
    }

    public function add(Cart $cart, ProductSnapshot $product, int $qty): CartItem
    {
        $item = $cart->items()->where('product_id', $product->id)->first();
        $newQty = ($item?->qty ?? 0) + $qty;

        if ($newQty > $product->stock) {
            throw new InsufficientStockException($product->name, $product->stock, $newQty);
        }

        if ($item !== null) {
            $item->update([
                'qty' => $newQty,
                'unit_price' => $product->price,
                'product_name' => $product->name,
            ]);

            return $item->refresh();
        }

        return $cart->items()->create([
            'product_id' => $product->id,
            'product_name' => $product->name,
            'unit_price' => $product->price,
            'qty' => $qty,
        ]);
    }

    public function updateQuantity(Cart $cart, CartItem $item, int $qty, ProductSnapshot $product): CartItem
    {
        abort_unless($item->cart_id === $cart->id, 403);

        if ($qty > $product->stock) {
            throw new InsufficientStockException($product->name, $product->stock, $qty);
        }

        $item->update([
            'qty' => $qty,
            'unit_price' => $product->price,
            'product_name' => $product->name,
        ]);

        return $item->refresh();
    }

    public function remove(Cart $cart, CartItem $item): void
    {
        abort_unless($item->cart_id === $cart->id, 403);
        $item->delete();
    }

    /**
     * @return array{subtotal: float, shipping: float, total: float}
     */
    public function totals(Cart $cart): array
    {
        $subtotal = $cart->subtotal();
        $shipping = Order::shippingFor($subtotal);

        return [
            'subtotal' => $subtotal,
            'shipping' => $shipping,
            'total' => round($subtotal + $shipping, 2),
        ];
    }
}
