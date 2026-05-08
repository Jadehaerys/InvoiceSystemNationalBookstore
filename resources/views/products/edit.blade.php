@extends('layouts.app')

@section('title', 'Edit Product')

@section('content')
    <div class="page-head">
        <div>
            <span class="badge">Catalog Update</span>
            <h1 class="page-title" style="margin-top: 12px;">Edit {{ $product->name }}</h1>
            <p class="page-subtitle">Update the product details and the POS screen will immediately use the latest price and description.</p>
        </div>
    </div>

    <section class="panel">
        <form action="{{ route('products.update', $product) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-grid">
                <div class="field">
                    <label for="name">Product Name</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $product->name) }}" required>
                </div>

                <div class="field">
                    <label for="price">Price</label>
                    <input type="number" id="price" name="price" min="0" step="0.01" value="{{ old('price', $product->price) }}" required>
                </div>

                <div class="field">
                    <label for="stock_quantity">Stock On Hand</label>
                    <input type="number" id="stock_quantity" name="stock_quantity" min="0" step="1" value="{{ old('stock_quantity', $product->stock_quantity) }}" required>
                </div>

                <div class="field field-full">
                    <label for="category">Category</label>
                    <input type="text" id="category" name="category" value="{{ old('category', $product->category) }}">
                </div>

                <div class="field field-full">
                    <label for="description">Description</label>
                    <textarea id="description" name="description">{{ old('description', $product->description) }}</textarea>
                </div>
            </div>

            <div class="inline-actions">
                <button type="submit" class="btn btn-primary">Save Changes</button>
                <a href="{{ route('products.show', $product) }}" class="btn btn-secondary">Back to details</a>
            </div>
        </form>
    </section>
@endsection