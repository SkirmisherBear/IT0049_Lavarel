@extends('layouts.pos')
@section('title', 'Edit Product')
@section('heading', 'Edit Product')

@section('content')
<div class="page-heading">
    <div>
        <h1>Edit Product</h1>
        <p class="muted">Update pricing, stock count, or item details.</p>
    </div>
    <a href="{{ route('products.index') }}" class="btn btn-secondary">← Back</a>
</div>

<div class="card form-card">
    <form method="POST" action="{{ route('products.update', $product) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label>Product Name</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $product->name) }}" required>
        </div>

        <div class="form-group">
            <label for="category">Category</label>
            <select id="category" name="category" class="form-control" required>
                @foreach ($categories as $category)
                    <option value="{{ $category }}" @selected(old('category', $product->category) === $category)>
                        {{ str($category)->replace('_', ' ')->title() }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label>Price (₱)</label>
            <input type="number" step="0.01" min="0" name="price" class="form-control" value="{{ old('price', $product->price) }}" required>
        </div>

        <div class="form-group">
            <label>Stock Quantity</label>
            <input type="number" min="0" name="stock_quantity" class="form-control" value="{{ old('stock_quantity', $product->stock_quantity) }}" required>
        </div>

        <div class="form-group">
            <label>Replace Image (Optional)</label>
            <input type="file" name="image" class="form-control" accept="image/*">
        </div>

        <div class="form-actions">
            <button type="submit" class="btn">Update Product</button>
            <a href="{{ route('products.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
