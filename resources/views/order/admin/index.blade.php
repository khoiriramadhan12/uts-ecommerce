@extends('layouts.app')

@section('title', 'Kelola Pesanan')

@section('content')
    <h1 class="h3 mb-3">Semua Pesanan</h1>

    <form class="row g-2 mb-3" method="GET">
        <div class="col-md-3">
            <select class="form-select" name="status">
                <option value="">Semua status</option>
                @foreach (['pending', 'paid', 'completed', 'cancelled'] as $status)
                    <option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst($status) }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-auto"><button class="btn btn-outline-dark" type="submit">Filter</button></div>
    </form>

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr><th>Kode</th><th>Pelanggan</th><th>Tanggal</th><th>Status</th><th>Total</th><th class="text-end">Aksi</th></tr>
                </thead>
                <tbody>
                    @forelse ($orders as $order)
                        <tr>
                            <td><code>{{ $order->code }}</code></td>
                            <td>{{ $customerNames[$order->id] ?? '-' }}</td>
                            <td>{{ $order->placed_at?->format('d M Y H:i') }}</td>
                            <td><span class="badge text-bg-{{ $order->statusBadge() }}">{{ $order->statusLabel() }}</span></td>
                            <td>Rp {{ number_format((float) $order->total, 0, ',', '.') }}</td>
                            <td class="text-end">
                                <a class="btn btn-sm btn-outline-dark" href="{{ route('admin.orders.show', $order->code) }}">Detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td class="text-center text-muted" colspan="6">Belum ada pesanan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">{{ $orders->links('pagination::bootstrap-5') }}</div>
@endsection
