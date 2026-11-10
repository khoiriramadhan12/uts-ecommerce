@extends('layouts.app')

@section('title', 'Kelola Kategori')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">Kategori</h1>
        <a class="btn btn-dark" href="{{ route('admin.catalog.categories.create') }}">Tambah Kategori</a>
    </div>

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr><th>Nama</th><th>Slug</th><th>Jumlah Produk</th><th>Status</th><th class="text-end">Aksi</th></tr>
                </thead>
                <tbody>
                    @forelse ($categories as $category)
                        <tr>
                            <td>{{ $category->name }}</td>
                            <td><code>{{ $category->slug }}</code></td>
                            <td>{{ $category->products_count }}</td>
                            <td>
                                @if ($category->is_active)
                                    <span class="badge text-bg-success">Aktif</span>
                                @else
                                    <span class="badge text-bg-secondary">Nonaktif</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <a class="btn btn-sm btn-outline-dark" href="{{ route('admin.catalog.categories.edit', $category) }}">Edit</a>
                                <form class="d-inline" method="POST" action="{{ route('admin.catalog.categories.destroy', $category) }}" onsubmit="return confirm('Hapus kategori ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" type="submit">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td class="text-center text-muted" colspan="5">Belum ada kategori.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">{{ $categories->links('pagination::bootstrap-5') }}</div>
@endsection
