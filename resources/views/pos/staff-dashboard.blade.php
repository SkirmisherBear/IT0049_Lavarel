@extends('layouts.pos')

@section('title', 'Staff Dashboard')
@section('heading', 'Staff Workspace')

@section('content')
<div class="page-heading">
    <div>
        <h1>Welcome, {{ auth()->user()->name ?? auth()->user()->username }}!</h1>
        <p class="muted">Your shift summary and tools for helping Overclock members.</p>
    </div>
    <a href="{{ route('sales.create') }}" class="btn">+ Process a Sale</a>
</div>

<div class="stats">
    <div class="card">
        <div class="stat-icon">₱</div>
        <div class="stat-label">My Revenue Today</div>
        <div class="stat-value">₱{{ number_format((float) $todaySales, 2) }}</div>
    </div>
    <div class="card">
        <div class="stat-icon">▤</div>
        <div class="stat-label">My Transactions Today</div>
        <div class="stat-value">{{ $todayTransactions }}</div>
    </div>
</div>

<div class="panel card">
    <div class="panel-title">My Recent Transactions</div>
    <div class="table-wrap">
        <table>
            <thead><tr><th>Receipt</th><th>Product</th><th>Customer</th><th>Qty</th><th>Total</th><th>Date</th></tr></thead>
            <tbody>
                @forelse($recentSales as $sale)
                    <tr>
                        <td>#{{ str_pad($sale->id, 5, '0', STR_PAD_LEFT) }}</td>
                        <td>{{ $sale->product?->name ?? 'Unavailable' }}</td>
                        <td>{{ $sale->customer?->full_name ?? 'Walk-in' }}</td>
                        <td>{{ $sale->quantity }}</td>
                        <td>₱{{ number_format((float) $sale->total_price, 2) }}</td>
                        <td>{{ $sale->created_at->format('M d, Y h:i A') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6" style="text-align:center;padding:24px;">You have no sales yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="panel">
    <div class="panel-title">Work Modules</div>
    <div style="display:flex;gap:12px;flex-wrap:wrap">
        <a href="{{ route('sales.create') }}" class="btn">Process a Sale</a>
        <a href="{{ route('sales.index') }}" class="btn">View My Sales</a>
        <a href="{{ route('products.index') }}" class="btn">Look Up Products</a>
        <a href="{{ route('customers.index') }}" class="btn">Look Up Customers</a>
    </div>
</div>
@endsection
