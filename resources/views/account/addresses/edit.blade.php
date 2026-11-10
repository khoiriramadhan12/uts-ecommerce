@extends('layouts.app')

@section('title', 'Edit Alamat')

@section('content')
    <h1 class="h3 mb-3">Edit Alamat</h1>
    <div class="card shadow-sm">
        <div class="card-body">
            <form method="POST" action="{{ route('account.addresses.update', $address->id) }}">
                @csrf
                @method('PUT')
                @include('account.addresses._form')
                <div class="mt-4">
                    <button class="btn btn-dark" type="submit">Perbarui</button>
                    <a class="btn btn-outline-secondary" href="{{ route('account.addresses.index') }}">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection
