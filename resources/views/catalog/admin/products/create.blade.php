@extends('layouts.app')

@section('title', 'Tambah Produk')

@section('content')
    <h1 class="h3 mb-3">Tambah Produk</h1>
    <div class="card shadow-sm">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.catalog.products.store') }}">
                @csrf
                @include('catalog.admin.products._form')
                <div class="mt-4 d-flex gap-2">
                    <button class="btn btn-dark" type="submit">Simpan</button>
                    <a class="btn btn-outline-secondary" href="{{ route('admin.catalog.products.index') }}">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection
