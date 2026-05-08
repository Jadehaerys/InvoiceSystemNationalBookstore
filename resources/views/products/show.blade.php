@extends('layouts.app')

@section('title', 'Product Details')

@section('content')
    <div class="page-head">
        <div>
            <span class="badge">Product Detail</span>
            <h1 class="page-title" style="margin-top: 12px;">{{ $product->name }}</h1>
            <p class="page-subtitle">Quick reference card for the selected product before it is used in checkout.</p>
        </div>

        <div class="inline-actions screen-only">
            <a href="{{ route('products.edit', $product) }}" class="btn btn-primary">Edit Product</a>
            <a href="{{ route('products.index') }}" class="btn btn-secondary">Back to catalog</a>
        </div>
    </div>

    <div class="grid-2">
        <section class="panel">
            <div class="stats-grid" style="grid-template-columns: repeat(3, minmax(0, 1fr)); margin-bottom: 0;">
                <article class="stat-card">
                    <span class="label">Category</span>
                    <strong class="stat-value" style="font-size: 24px;">{{ $product->category ?: 'Uncategorized' }}</strong>
                </article>
                <article class="stat-card">
                    <span class="label">Selling Price</span>
                    <strong class="stat-value mono" style="font-size: 24px;">{{ number_format((float) $product->price, 2) }}</strong>
                </article>
                <article class="stat-card">
                    <span class="label">Stock On Hand</span>
                    <strong class="stat-value mono" style="font-size: 24px;">{{ $product->stock_quantity }}</strong>
                </article>
            </div>
        </section>

        <section class="panel">
            <span class="label">Description</span>
            <p class="page-subtitle" style="margin-top: 12px; max-width: none;">{{ $product->description ?: 'No description provided for this catalog item.' }}</p>
        </section>
    </div>
@endsection
