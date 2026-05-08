@extends('layouts.app')

@section('title', 'Edit Customer')

@section('content')
    <div class="page-head">
        <div>
            <span class="badge">Buyer Profile Update</span>
            <h1 class="page-title" style="margin-top: 12px;">Edit {{ $customer->name }}</h1>
            <p class="page-subtitle">Keep saved receipt details accurate for future transactions.</p>
        </div>
    </div>

    <section class="panel">
        <form action="{{ route('customers.update', $customer) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-grid">
                <div class="field">
                    <label for="name">Full Name</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $customer->name) }}" required>
                </div>

                <div class="field">
                    <label for="contact_number">Contact Number</label>
                    <input type="text" id="contact_number" name="contact_number" value="{{ old('contact_number', $customer->contact_number) }}" required>
                </div>

                <div class="field field-full">
                    <label for="address">Address</label>
                    <textarea id="address" name="address" required>{{ old('address', $customer->address) }}</textarea>
                </div>
            </div>

            <div class="inline-actions">
                <button type="submit" class="btn btn-primary">Save Changes</button>
                <a href="{{ route('customers.show', $customer) }}" class="btn btn-secondary">Back to profile</a>
            </div>
        </form>
    </section>
@endsection