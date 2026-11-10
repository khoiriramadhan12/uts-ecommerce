@extends('layouts.app')

@section('title', 'Edit Produk')

@section('content')
    <h1 class="h3 mb-3">Edit Produk</h1>
    <div class="card shadow-sm">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.catalog.products.update', $product) }}">
                @csrf
                @method('PUT')
                @include('catalog.admin.products._form')
                <div class="mt-4 d-flex gap-2">
                    <button class="btn btn-dark" type="submit">Perbarui</button>
                    <a class="btn btn-outline-secondary" href="{{ route('admin.catalog.products.index') }}">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection
