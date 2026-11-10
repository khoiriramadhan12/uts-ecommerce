@extends('layouts.app')

@section('title', 'Katalog Produk')

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <h1 class="h3 mb-0">Katalog Produk</h1>
        @auth
            @if (auth()->user()->isAdmin())
                <a class="btn btn-outline-dark btn-sm" href="{{ route('admin.catalog.products.index') }}">Kelola Produk</a>
            @endif
        @endauth
    </div>

    <form class="row g-2 mb-4" method="GET" action="{{ route('catalog.index') }}">
        <div class="col-md-5">
            <input class="form-control" name="q" value="{{ request('q') }}" placeholder="Cari nama produk...">
        </div>
        <div class="col-md-4">
            <select class="form-select" name="category">
                <option value="">Semua kategori</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->slug }}" @selected(request('category') === $category->slug)>{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <button class="btn btn-dark w-100" type="submit">Cari</button>
        </div>
    </form>

    <div class="row g-4">
        @forelse ($products as $product)
            <div class="col-sm-6 col-lg-4">
                <div class="card card-product shadow-sm">
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-start">
                            <span class="badge text-bg-secondary">{{ $product->category?->name ?? 'Tanpa kategori' }}</span>
                            @if ($product->stock > 0)
                                <span class="badge text-bg-success">Stok {{ $product->stock }}</span>
                            @else
                                <span class="badge text-bg-danger">Stok habis</span>
                            @endif
                        </div>
                        <h2 class="h5 mt-2">{{ $product->name }}</h2>
                        <p class="text-muted small mb-2">SKU {{ $product->sku }}</p>
                        <p class="fw-semibold mb-2">Rp {{ number_format((float) $product->price, 0, ',', '.') }}</p>
                        <div class="mb-3">
                            @foreach ($product->tags as $tag)
                                <span class="badge text-bg-light border">{{ $tag->name }}</span>
                            @endforeach
                        </div>
                        <div class="mt-auto d-flex gap-2">
                            <a class="btn btn-outline-dark btn-sm" href="{{ route('catalog.show', $product->slug) }}">Detail</a>
                            @auth
                                @if (auth()->user()->isCustomer() && $product->stock > 0)
                                    <form class="d-flex gap-1" method="POST" action="{{ route('cart.store') }}">
                                        @csrf
                                        <input name="product_id" type="hidden" value="{{ $product->id }}">
                                        <input class="form-control form-control-sm" name="qty" type="number" value="1" min="1" max="{{ min(99, $product->stock) }}" style="width: 70px">
                                        <button class="btn btn-dark btn-sm" type="submit">Beli</button>
                                    </form>
                                @endif
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info">Produk tidak ditemukan.</div>
            </div>
        @endforelse
    </div>

    <div class="mt-4">{{ $products->links('pagination::bootstrap-5') }}</div>
@endsection
