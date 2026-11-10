@extends('layouts.app')

@section('title', 'Kelola Tag')

@section('content')
    <h1 class="h3 mb-3">Tag</h1>

    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h2 class="h6 mb-3">Tambah Tag</h2>
                    <form method="POST" action="{{ route('admin.catalog.tags.store') }}">
                        @csrf
                        <div class="mb-3">
                            <input class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" placeholder="Nama tag" required>
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <button class="btn btn-dark w-100" type="submit">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light"><tr><th>Nama</th><th>Slug</th><th>Produk</th><th class="text-end">Aksi</th></tr></thead>
                        <tbody>
                            @forelse ($tags as $tag)
                                <tr>
                                    <td>{{ $tag->name }}</td>
                                    <td><code>{{ $tag->slug }}</code></td>
                                    <td>{{ $tag->products_count }}</td>
                                    <td class="text-end">
                                        <form class="d-inline" method="POST" action="{{ route('admin.catalog.tags.destroy', $tag) }}" onsubmit="return confirm('Hapus tag ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger" type="submit">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td class="text-center text-muted" colspan="4">Belum ada tag.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="mt-3">{{ $tags->links('pagination::bootstrap-5') }}</div>
        </div>
    </div>
@endsection
