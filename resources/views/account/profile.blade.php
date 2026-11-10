@extends('layouts.app')

@section('title', 'Profil')

@section('content')
    <h1 class="h3 mb-3">Profil Saya</h1>
    <div class="row g-4">
        <div class="col-lg-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <form method="POST" action="{{ route('account.profile.update') }}">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label class="form-label" for="full_name">Nama lengkap</label>
                            <input class="form-control @error('full_name') is-invalid @enderror" id="full_name" name="full_name" value="{{ old('full_name', $customer->fullName) }}" required>
                            @error('full_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="phone">Nomor telepon</label>
                            <input class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', $customer->phone) }}">
                            @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <p class="small text-muted">Email: {{ auth()->user()->email }}</p>
                        <button class="btn btn-dark" type="submit">Simpan Perubahan</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h2 class="h6">Alamat Pengiriman</h2>
                    <p class="small text-muted">Kelola alamat yang dipakai saat checkout.</p>
                    <a class="btn btn-outline-dark" href="{{ route('account.addresses.index') }}">Kelola Alamat</a>
                </div>
            </div>
        </div>
    </div>
@endsection
