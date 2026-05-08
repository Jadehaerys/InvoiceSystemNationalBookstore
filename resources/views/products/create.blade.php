@extends('layouts.app')

@section('title', 'Add Product')

@section('content')
    <div class="page-head">
        <div>
            <span class="badge">Catalog Entry</span>
            <h1 class="page-title" style="margin-top: 12px;">Add a new product</h1>
            <p class="page-subtitle">Create a book or supply entry that will appear as a selectable line item in the POS cart.</p>
        </div>
    </div>

    <section class="panel">
        <form action="{{ route('products.store') }}" method="POST">
            @csrf

            <div class="form-grid">
                <div class="field">
                    <label for="name">Product Name</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required>
                </div>

                <div class="field">
                    <label for="price">Price</label>
                    <input type="number" id="price" name="price" min="0" step="0.01" value="{{ old('price') }}" required>
                </div>

                <div class="field field-full">
                    <label for="category">Category</label>
                    <input type="text" id="category" name="category" value="{{ old('category') }}" placeholder="Books, School Supplies, Review Materials">
                </div>

                <div class="field field-full">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" placeholder="Short product description for the cashier and receipt history.">{{ old('description') }}</textarea>
                </div>
            </div>

            <div class="inline-actions">
                <button type="submit" class="btn btn-primary">Save Product</button>
                <a href="{{ route('products.index') }}" class="btn btn-secondary">Back to catalog</a>
            </div>
        </form>
    </section>
@endsection