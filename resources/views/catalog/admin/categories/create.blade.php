@extends('layouts.app')

@section('title', 'Tambah Kategori')

@section('content')
    <h1 class="h3 mb-3">Tambah Kategori</h1>
    <div class="card shadow-sm">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.catalog.categories.store') }}">
                @csrf
                @include('catalog.admin.categories._form')
                <button class="btn btn-dark" type="submit">Simpan</button>
                <a class="btn btn-outline-secondary" href="{{ route('admin.catalog.categories.index') }}">Batal</a>
            </form>
        </div>
    </div>
@endsection
