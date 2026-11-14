@extends('layouts.pos')
@section('title', 'Products')
@section('heading', 'Products Inventory')

@section('content')
<div class="page-heading">
    <div>
        <h1>Products</h1>
        <p class="muted">Manage gym passes, supplements, drinks, and merchandise stock.</p>
    </div>
    <a href="{{ route('products.create') }}" class="btn">+ Add Product</a>
</div>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Product Name</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                    <tr>
                        <td>#{{ $product->id }}</td>
                        <td><strong>{{ $product->name }}</strong></td>
                        <td>₱{{ number_format((float)$product->price, 2) }}</td>
                        <td>{{ $product->stock_quantity }}</td>
                        <td>
                            @if($product->stock_quantity <= 5)
                                <span class="badge badge-low">Low Stock</span>
                            @else
                                <span class="badge badge-ok">In Stock</span>
                            @endif
                        </td>
                        <td class="actions-cell">
                            <a href="{{ route('products.edit', $product) }}" class="btn btn-secondary">Edit</a>
                            <form method="POST" action="{{ route('products.destroy', $product) }}" onsubmit="return confirm('Delete this product?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align:center;padding:30px">No products found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection