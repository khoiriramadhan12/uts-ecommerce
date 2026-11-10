@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
    <h1 class="h3 mb-3">Checkout</h1>

    <form method="POST" action="{{ route('checkout.store') }}">
        @csrf
        <div class="row g-4">
            <div class="col-lg-7">
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h2 class="h6 text-uppercase text-muted">Alamat Pengiriman</h2>
                        @forelse ($addresses as $address)
                            <div class="form-check border rounded p-3 mb-2">
                                <input class="form-check-input" id="address-{{ $address->id }}" name="address_id" type="radio" value="{{ $address->id }}" @checked(old('address_id', $address->isDefault ? $address->id : null) == $address->id)>
                                <label class="form-check-label" for="address-{{ $address->id }}">
                                    <span class="fw-semibold">{{ $address->label }}</span>
                                    @if ($address->isDefault) <span class="badge text-bg-success">Utama</span> @endif
                                    <br>{{ $address->recipientName }} · {{ $address->phone }}
                                    <br><span class="text-muted small">{{ $address->fullAddress() }}</span>
                                </label>
                            </div>
                        @empty
                            <div class="alert alert-warning mb-0">
                                Belum ada alamat. <a href="{{ route('account.addresses.create') }}">Tambahkan alamat</a> dulu.
                            </div>
                        @endforelse
                    </div>
                </div>

                <div class="card shadow-sm">
                    <div class="card-body">
                        <h2 class="h6 text-uppercase text-muted">Item Pesanan</h2>
                        <ul class="list-group list-group-flush">
                            @foreach ($cart->items as $item)
                                <li class="list-group-item d-flex justify-content-between px-0">
                                    <span>{{ $item->product_name }} × {{ $item->qty }}</span>
                                    <span>Rp {{ number_format($item->lineTotal(), 0, ',', '.') }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h2 class="h6 text-uppercase text-muted">Ringkasan</h2>
                        <dl class="row mb-0">
                            <dt class="col-7 fw-normal">Subtotal</dt>
                            <dd class="col-5 text-end">Rp {{ number_format($totals['subtotal'], 0, ',', '.') }}</dd>
                            <dt class="col-7 fw-normal">Ongkir</dt>
                            <dd class="col-5 text-end">Rp {{ number_format($totals['shipping'], 0, ',', '.') }}</dd>
                            <dt class="col-7 border-top pt-2">Total</dt>
                            <dd class="col-5 border-top pt-2 text-end fw-semibold">Rp {{ number_format($totals['total'], 0, ',', '.') }}</dd>
                        </dl>
                        <button class="btn btn-dark w-100 mt-3" type="submit" @disabled($addresses === [])>Buat Pesanan</button>
                        <p class="small text-muted mt-2 mb-0">Stok akan diperiksa ulang saat pesanan dibuat.</p>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
