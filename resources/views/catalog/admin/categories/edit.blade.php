@extends('layouts.app')

@section('title', 'Edit Kategori')

@section('content')
    <h1 class="h3 mb-3">Edit Kategori</h1>
    <div class="card shadow-sm">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.catalog.categories.update', $category) }}">
                @csrf
                @method('PUT')
                @include('catalog.admin.categories._form')
                <button class="btn btn-dark" type="submit">Perbarui</button>
                <a class="btn btn-outline-secondary" href="{{ route('admin.catalog.categories.index') }}">Batal</a>
            </form>
        </div>
    </div>
@endsection
