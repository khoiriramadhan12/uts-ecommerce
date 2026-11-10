@extends('layouts.app')

@section('title', 'Detail Pesanan')

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <h1 class="h3 mb-0">Pesanan <code>{{ $order->code }}</code></h1>
        <span class="badge text-bg-{{ $order->statusBadge() }} fs-6">{{ $order->statusLabel() }}</span>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="table-light">
                            <tr><th>Produk</th><th>SKU</th><th>Harga</th><th>Qty</th><th>Subtotal</th></tr>
                        </thead>
                        <tbody>
                            @foreach ($order->items as $item)
                                <tr>
                                    <td>{{ $item->product_name }}</td>
                                    <td>{{ $item->product_sku }}</td>
                                    <td>Rp {{ number_format((float) $item->unit_price, 0, ',', '.') }}</td>
                                    <td>{{ $item->qty }}</td>
                                    <td>Rp {{ number_format((float) $item->line_total, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-body">
                    <h2 class="h6 text-uppercase text-muted">Alamat Pengiriman</h2>
                    <p class="mb-1 fw-semibold">{{ $order->shipping_snapshot['recipient_name'] ?? '-' }}</p>
                    <p class="mb-1">{{ $order->shipping_snapshot['phone'] ?? '-' }}</p>
                    <p class="text-muted mb-0">{{ $order->shipping_snapshot['full_address'] ?? '-' }}</p>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h2 class="h6 text-uppercase text-muted">Data Pesanan</h2>
                    <p class="mb-1"><span class="text-muted">Pelanggan:</span> {{ $customerName }}</p>
                    <p class="mb-3"><span class="text-muted">Dibuat:</span> {{ $order->placed_at?->format('d M Y H:i') }}</p>
                    <dl class="row mb-0">
                        <dt class="col-7 fw-normal">Subtotal</dt>
                        <dd class="col-5 text-end">Rp {{ number_format((float) $order->subtotal, 0, ',', '.') }}</dd>
                        <dt class="col-7 fw-normal">Ongkir</dt>
                        <dd class="col-5 text-end">Rp {{ number_format((float) $order->shipping_cost, 0, ',', '.') }}</dd>
                        <dt class="col-7 border-top pt-2">Total</dt>
                        <dd class="col-5 border-top pt-2 text-end fw-semibold">Rp {{ number_format((float) $order->total, 0, ',', '.') }}</dd>
                    </dl>

                    @if ($order->status === \App\Modules\Order\Models\Order::STATUS_PAID)
                        <form class="mt-3" method="POST" action="{{ route('admin.orders.complete', $order->id) }}">
                            @csrf
                            <button class="btn btn-dark w-100" type="submit">Tandai Selesai</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
