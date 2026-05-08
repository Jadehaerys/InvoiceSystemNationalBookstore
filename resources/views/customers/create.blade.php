@extends('layouts.app')

@section('title', 'Add Customer')

@section('content')
    <div class="page-head">
        <div>
            <span class="badge">New Buyer Profile</span>
            <h1 class="page-title" style="margin-top: 12px;">Create a customer record</h1>
            <p class="page-subtitle">Optional for each sale, but useful when you want the printed receipt to show a saved buyer profile.</p>
        </div>
    </div>

    <section class="panel">
        <form action="{{ route('customers.store') }}" method="POST">
            @csrf

            <div class="form-grid">
                <div class="field">
                    <label for="name">Full Name</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required>
                </div>

                <div class="field">
                    <label for="contact_number">Contact Number</label>
                    <input type="text" id="contact_number" name="contact_number" value="{{ old('contact_number') }}" required>
                </div>

                <div class="field field-full">
                    <label for="address">Address</label>
                    <textarea id="address" name="address" required>{{ old('address') }}</textarea>
                </div>
            </div>

            <div class="inline-actions">
                <button type="submit" class="btn btn-primary">Save Customer</button>
                <a href="{{ route('customers.index') }}" class="btn btn-secondary">Back to customers</a>
            </div>
        </form>
    </section>
@endsection