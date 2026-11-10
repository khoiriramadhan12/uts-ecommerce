@extends('layouts.app')

@section('title', 'Alamat')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">Alamat Pengiriman</h1>
        <a class="btn btn-dark" href="{{ route('account.addresses.create') }}">Tambah Alamat</a>
    </div>

    <div class="row g-3">
        @forelse ($addresses as $address)
            <div class="col-md-6">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <h2 class="h6 mb-1">{{ $address->label }}</h2>
                            <div>
                                @if ($address->isDefault)
                                    <span class="badge text-bg-success">Utama</span>
                                @endif
                                <span class="badge text-bg-light border">{{ $address->postalCode }}</span>
                            </div>
                        </div>
                        <p class="mb-1 fw-semibold">{{ $address->recipientName }} · {{ $address->phone }}</p>
                        <p class="text-muted small mb-3">{{ $address->fullAddress() }}</p>
                        <div class="d-flex gap-2">
                            <a class="btn btn-sm btn-outline-dark" href="{{ route('account.addresses.edit', $address->id) }}">Edit</a>
                            <form method="POST" action="{{ route('account.addresses.destroy', $address->id) }}" onsubmit="return confirm('Hapus alamat ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" type="submit">Hapus</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12"><div class="alert alert-info">Belum ada alamat. Tambahkan alamat agar bisa checkout.</div></div>
        @endforelse
    </div>
@endsection
