@extends('layouts.app')

@section('title', 'Kelola Produk')

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <h1 class="h3 mb-0">Produk</h1>
        <a class="btn btn-dark" href="{{ route('admin.catalog.products.create') }}">Tambah Produk</a>
    </div>

    <form class="row g-2 mb-3" method="GET">
        <div class="col-md-4">
            <input class="form-control" name="q" value="{{ request('q') }}" placeholder="Cari nama produk...">
        </div>
        <div class="col-auto"><button class="btn btn-outline-dark" type="submit">Cari</button></div>
    </form>

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Nama</th>
                        <th>SKU</th>
                        <th>Kategori</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th>Status</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($products as $product)
                        <tr>
                            <td>
                                <div class="fw-semibold">{{ $product->name }}</div>
                                <div class="small text-muted">
                                    @foreach ($product->tags as $tag)
                                        <span class="badge text-bg-light border">{{ $tag->name }}</span>
                                    @endforeach
                                </div>
                            </td>
                            <td>{{ $product->sku }}</td>
                            <td>{{ $product->category?->name }}</td>
                            <td>Rp {{ number_format((float) $product->price, 0, ',', '.') }}</td>
                            <td>{{ $product->stock }}</td>
                            <td>
                                @if ($product->is_active)
                                    <span class="badge text-bg-success">Aktif</span>
                                @else
                                    <span class="badge text-bg-secondary">Nonaktif</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <a class="btn btn-sm btn-outline-dark" href="{{ route('admin.catalog.products.edit', $product) }}">Edit</a>
                                <form class="d-inline" method="POST" action="{{ route('admin.catalog.products.destroy', $product) }}" onsubmit="return confirm('Hapus produk ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" type="submit">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td class="text-center text-muted" colspan="7">Belum ada produk.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">{{ $products->links('pagination::bootstrap-5') }}</div>
@endsection
