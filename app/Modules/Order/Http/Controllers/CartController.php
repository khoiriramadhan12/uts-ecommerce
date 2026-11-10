<?php

namespace App\Modules\Order\Http\Controllers;

use App\Modules\Catalog\Contracts\ProductCatalogContract;
use App\Modules\Customer\Contracts\CustomerProfileContract;
use App\Modules\Order\Http\Requests\AddToCartRequest;
use App\Modules\Order\Http\Requests\UpdateCartItemRequest;
use App\Modules\Order\Models\CartItem;
use App\Modules\Order\Services\CartService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController
{
    public function __construct(
        private readonly ProductCatalogContract $catalog,
        private readonly CustomerProfileContract $customers,
        private readonly CartService $carts,
    ) {}

    public function index(Request $request): View
    {
        $cart = $this->carts->activeCartFor($this->customerId($request));

        return view('order.cart', [
            'cart' => $cart,
            'totals' => $this->carts->totals($cart),
        ]);
    }

    public function store(AddToCartRequest $request): RedirectResponse
    {
        $cart = $this->carts->activeCartFor($this->customerId($request));
        $product = $this->catalog->getForCheckout($request->integer('product_id'));

        $this->carts->add($cart, $product, $request->integer('qty'));

        return back()->with('success', $product->name.' ditambahkan ke keranjang.');
    }

    public function update(UpdateCartItemRequest $request, CartItem $cartItem): RedirectResponse
    {
        $cart = $this->carts->activeCartFor($this->customerId($request));
        $product = $this->catalog->getForCheckout($cartItem->product_id);

        $this->carts->updateQuantity($cart, $cartItem, $request->integer('qty'), $product);

        return redirect()->route('cart.index')->with('success', 'Jumlah item diperbarui.');
    }

    public function destroy(Request $request, CartItem $cartItem): RedirectResponse
    {
        $cart = $this->carts->activeCartFor($this->customerId($request));
        $this->carts->remove($cart, $cartItem);

        return redirect()->route('cart.index')->with('success', 'Item dihapus dari keranjang.');
    }

    private function customerId(Request $request): int
    {
        return $this->customers->findByUserId($request->user()->id)->id;
    }
}
