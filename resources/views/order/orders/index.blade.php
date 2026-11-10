@extends('layouts.app')

@section('title', 'Pesanan Saya')

@section('content')
    <h1 class="h3 mb-3">Pesanan Saya</h1>

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr><th>Kode</th><th>Tanggal</th><th>Status</th><th>Total</th><th class="text-end">Aksi</th></tr>
                </thead>
                <tbody>
                    @forelse ($orders as $order)
                        <tr>
                            <td><code>{{ $order->code }}</code></td>
                            <td>{{ $order->placed_at?->format('d M Y H:i') }}</td>
                            <td><span class="badge text-bg-{{ $order->statusBadge() }}">{{ $order->statusLabel() }}</span></td>
                            <td>Rp {{ number_format((float) $order->total, 0, ',', '.') }}</td>
                            <td class="text-end">
                                <a class="btn btn-sm btn-outline-dark" href="{{ route('orders.show', $order->code) }}">Detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td class="text-center text-muted" colspan="5">Belum ada pesanan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">{{ $orders->links('pagination::bootstrap-5') }}</div>
@endsection
