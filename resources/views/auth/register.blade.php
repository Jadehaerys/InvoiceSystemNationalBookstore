@extends('layouts.app')

@section('title', 'Register')

@section('content')
    <div class="auth-shell">
        <section class="panel auth-card">
            <span class="badge">Create Cashier Account</span>
            <div class="page-head" style="margin: 18px 0 24px;">
                <div>
                    <h1 class="page-title" style="font-size: clamp(2rem, 6vw, 3rem);">Register a new terminal user</h1>
                    <p class="page-subtitle">This keeps the project simple: one login opens the POS, then you can manage products, customers, and receipt generation from the same dashboard.</p>
                </div>
            </div>

            <form action="{{ route('register.store') }}" method="POST" class="stack">
                @csrf

                <div class="field">
                    <label for="name">Full Name</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required>
                </div>

                <div class="field">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required>
                </div>

                <div class="form-grid" style="margin-bottom: 0;">
                    <div class="field">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" required>
                    </div>

                    <div class="field">
                        <label for="password_confirmation">Confirm Password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" required>
                    </div>
                </div>

                <div class="inline-actions" style="margin-top: 6px;">
                    <button type="submit" class="btn btn-primary">Create Account</button>
                    <a href="{{ route('login') }}" class="btn btn-secondary">Back to login</a>
                </div>
            </form>
        </section>
    </div>
@endsection