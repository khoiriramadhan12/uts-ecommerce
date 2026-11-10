<?php

namespace App\Modules\Order\Services;

use App\Modules\Catalog\Contracts\ProductCatalogContract;
use App\Modules\Catalog\DTO\ProductSnapshot;
use App\Modules\Catalog\Exceptions\ProductNotFoundException;
use App\Modules\Customer\Contracts\CustomerProfileContract;
use App\Modules\Order\Events\OrderPlaced;
use App\Modules\Order\Exceptions\EmptyCartException;
use App\Modules\Order\Models\Cart;
use App\Modules\Order\Models\Order;
use Illuminate\Support\Facades\DB;

class CheckoutService
{
    public function __construct(
        private readonly ProductCatalogContract $catalog,
        private readonly CustomerProfileContract $customers,
    ) {}

    /**
     * Seluruh langkah berjalan dalam satu transaksi: gagal di tengah berarti
     * stok kembali utuh dan tidak ada pesanan setengah jadi.
     */
    public function checkout(int $userId, int $addressId): Order
    {
        $customer = $this->customers->findByUserId($userId);

        [$order, $eventItems] = DB::transaction(function () use ($customer, $addressId): array {
            $address = $this->customers->assertAddressBelongsTo($customer->id, $addressId);

            $cart = Cart::query()
                ->where('customer_id', $customer->id)
                ->active()
                ->lockForUpdate()
                ->first();

            if ($cart === null) {
                throw new EmptyCartException;
            }

            $cart->load('items');

            if ($cart->items->isEmpty()) {
                throw new EmptyCartException;
            }

            $lines = [];
            $subtotal = 0.0;

            foreach ($cart->items as $item) {
                $snapshot = $this->catalog->getForCheckout($item->product_id);

                if (! $snapshot->isActive) {
                    throw new ProductNotFoundException($item->product_id);
                }

                $this->catalog->reserveStock($snapshot->id, $item->qty);

                $lineTotal = round($snapshot->price * $item->qty, 2);
                $subtotal = round($subtotal + $lineTotal, 2);

                $lines[] = [
                    'product' => $snapshot,
                    'qty' => $item->qty,
                    'line_total' => $lineTotal,
                ];
            }

            $shipping = Order::shippingFor($subtotal);

            $order = Order::create([
                'code' => $this->generateCode(),
                'customer_id' => $customer->id,
                'address_id' => $address->id,
                'status' => Order::STATUS_PENDING,
                'subtotal' => $subtotal,
                'shipping_cost' => $shipping,
                'total' => round($subtotal + $shipping, 2),
                'shipping_snapshot' => $address->toArray(),
                'placed_at' => now(),
            ]);

            foreach ($lines as $line) {
                /** @var ProductSnapshot $product */
                $product = $line['product'];

                $order->items()->create([
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'product_sku' => $product->sku,
                    'unit_price' => $product->price,
                    'qty' => $line['qty'],
                    'line_total' => $line['line_total'],
                ]);
            }

            $cart->items()->delete();
            $cart->update(['status' => Cart::STATUS_CONVERTED]);

            $eventItems = array_map(
                fn (array $line): array => ['product_id' => $line['product']->id, 'qty' => $line['qty']],
                $lines,
            );

            return [$order->load('items'), $eventItems];
        });

        // Event dipancarkan setelah transaksi commit agar kegagalan side effect
        // tidak membatalkan pesanan yang sudah sah.
        OrderPlaced::dispatch($order->id, $order->code, $customer->id, $eventItems);

        return $order;
    }

    private function generateCode(): string
    {
        do {
            $code = 'ORD-'.strtoupper(bin2hex(random_bytes(4)));
        } while (Order::where('code', $code)->exists());

        return $code;
    }
}
