@extends('layouts.app')

@section('title', 'Keranjang')

@section('content')
    <h1 class="h3 mb-3">Keranjang Belanja</h1>

    @if ($cart->items->isEmpty())
        <div class="alert alert-info">
            Keranjang masih kosong. <a href="{{ route('catalog.index') }}">Lihat katalog</a>.
        </div>
    @else
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="table-light">
                                <tr><th>Produk</th><th>Harga</th><th style="width:150px">Jumlah</th><th>Subtotal</th><th></th></tr>
                            </thead>
                            <tbody>
                                @foreach ($cart->items as $item)
                                    <tr>
                                        <td>{{ $item->product_name }}</td>
                                        <td>Rp {{ number_format((float) $item->unit_price, 0, ',', '.') }}</td>
                                        <td>
                                            <form class="d-flex gap-1" method="POST" action="{{ route('cart.update', $item) }}">
                                                @csrf
                                                @method('PATCH')
                                                <input class="form-control form-control-sm" name="qty" type="number" min="1" max="99" value="{{ $item->qty }}">
                                                <button class="btn btn-sm btn-outline-dark" type="submit">Ubah</button>
                                            </form>
                                        </td>
                                        <td>Rp {{ number_format($item->lineTotal(), 0, ',', '.') }}</td>
                                        <td class="text-end">
                                            <form method="POST" action="{{ route('cart.destroy', $item) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-outline-danger" type="submit">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
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
                        <a class="btn btn-dark w-100 mt-3" href="{{ route('checkout.create') }}">Lanjut ke Checkout</a>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
