@extends('layouts.pos')
@section('title', 'Sales History')
@section('heading', 'Sales History')

@section('content')
<div class="page-heading">
    <div>
        <h1>Sales History</h1>
        <p class="muted">Complete log of all POS transactions.</p>
    </div>
    <a href="{{ route('sales.create') }}" class="btn">+ New Sale</a>
</div>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Receipt</th>
                    <th>Product</th>
                    <th>Customer</th>
                    <th>Qty</th>
                    <th>Total</th>
                    <th>Cashier</th>
                    <th>Date & Time</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sales as $sale)
                    <tr>
                        <td>#{{ str_pad($sale->id, 5, '0', STR_PAD_LEFT) }}</td>
                        <td><strong>{{ $sale->product?->name ?? 'Unavailable' }}</strong></td>
                        <td>{{ $sale->customer?->full_name ?? 'Walk-in' }}</td>
                        <td>{{ $sale->quantity }}</td>
                        <td>₱{{ number_format((float)$sale->total_price, 2) }}</td>
                        <td>{{ $sale->staff?->username ?? $sale->staff?->name ?? 'Staff' }}</td>
                        <td>{{ $sale->created_at->format('M d, Y h:i A') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align:center;padding:30px">No transactions recorded yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection