@extends('layouts.app')

@section('title', 'Products')

@section('content')
    <div class="page-head">
        <div>
            <span class="badge">Product Catalog</span>
            <h1 class="page-title" style="margin-top: 12px;">Books and supplies inventory</h1>
            <p class="page-subtitle">Keep the POS catalog clean. These products become selectable line items inside the checkout screen.</p>
        </div>

        <div class="inline-actions screen-only">
            <a href="{{ route('products.create') }}" class="btn btn-primary">Add Product</a>
        </div>
    </div>

    <section class="stats-grid">
        <article class="stat-card">
            <span class="label">Catalog Size</span>
            <strong class="stat-value">{{ $products->count() }}</strong>
            <span class="stat-note">Active products in the POS list</span>
        </article>
        <article class="stat-card">
            <span class="label">Average Price</span>
            <strong class="stat-value mono">{{ number_format((float) $products->avg('price'), 2) }}</strong>
            <span class="stat-note">Average selling price across all items</span>
        </article>
        <article class="stat-card">
            <span class="label">Stock On Hand</span>
            <strong class="stat-value">{{ $products->sum('stock_quantity') }}</strong>
            <span class="stat-note">Total remaining inventory across all products</span>
        </article>
    </section>

    <section class="panel">
        @if ($products->isEmpty())
            <div class="empty-state">
                No products yet. Add at least one book or school supply before opening the POS screen.
            </div>
        @else
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Category</th>
                            <th>Stock</th>
                            <th>Price</th>
                            <th>Description</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $product)
                            <tr>
                                <td><strong>{{ $product->name }}</strong></td>
                                <td>{{ $product->category ?: 'Uncategorized' }}</td>
                                <td class="mono">{{ $product->stock_quantity }}</td>
                                <td class="mono">{{ number_format((float) $product->price, 2) }}</td>
                                <td class="muted">{{ $product->description ?: 'No description provided.' }}</td>
                                <td>
                                    <div class="table-actions">
                                        <a href="{{ route('products.show', $product) }}" class="btn btn-secondary">View</a>
                                        <a href="{{ route('products.edit', $product) }}" class="btn btn-ghost">Edit</a>
                                        <form action="{{ route('products.destroy', $product) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger" onclick="return confirm('Delete this product?')">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </section>
@endsection