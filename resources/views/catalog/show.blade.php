@extends('layouts.app')

@section('title', $product->name)

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('catalog.index') }}">Katalog</a></li>
            <li class="breadcrumb-item active">{{ $product->name }}</li>
        </ol>
    </nav>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    <span class="badge text-bg-secondary">{{ $product->category?->name }}</span>
                    <h1 class="h3 mt-2">{{ $product->name }}</h1>
                    <p class="text-muted">SKU {{ $product->sku }} · Terjual {{ $product->sold_count }}</p>
                    <p class="fs-4 fw-semibold">Rp {{ number_format((float) $product->price, 0, ',', '.') }}</p>
                    <p>{{ $product->description }}</p>
                    <div class="mb-3">
                        @foreach ($product->tags as $tag)
                            <span class="badge text-bg-light border">{{ $tag->name }}</span>
                        @endforeach
                    </div>
                    <p>
                        @if ($product->stock > 0)
                            <span class="badge text-bg-success">Stok tersedia: {{ $product->stock }}</span>
                        @else
                            <span class="badge text-bg-danger">Stok habis</span>
                        @endif
                    </p>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h2 class="h6 text-uppercase text-muted">Beli</h2>
                    @auth
                        @if (auth()->user()->isCustomer())
                            @if ($product->stock > 0)
                                <form method="POST" action="{{ route('cart.store') }}">
                                    @csrf
                                    <input name="product_id" type="hidden" value="{{ $product->id }}">
                                    <label class="form-label" for="qty">Jumlah</label>
                                    <input class="form-control mb-3" id="qty" name="qty" type="number" value="1" min="1" max="{{ min(99, $product->stock) }}">
                                    <button class="btn btn-dark w-100" type="submit">Tambah ke Keranjang</button>
                                </form>
                            @else
                                <p class="text-danger mb-0">Produk sedang tidak tersedia.</p>
                            @endif
                        @else
                            <p class="small text-muted mb-0">Login sebagai pelanggan untuk membeli produk.</p>
                        @endif
                    @else
                        <p class="small mb-2">Silakan login untuk membeli produk.</p>
                        <a class="btn btn-dark w-100" href="{{ route('login') }}">Login</a>
                    @endauth
                </div>
            </div>
        </div>
    </div>
@endsection
