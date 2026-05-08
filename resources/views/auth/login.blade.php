@extends('layouts.app')

@section('title', 'Log In')

@section('content')
    <div class="auth-shell">
        <section class="panel auth-card">
            <span class="badge">Bookstore Cashier Access</span>
            <div class="page-head" style="margin: 18px 0 24px;">
                <div>
                    <h1 class="page-title" style="font-size: clamp(2rem, 6vw, 3rem);">Sign in to start a sale</h1>
                    <p class="page-subtitle">Use the seeded cashier account to test the full POS flow immediately: <span class="mono">cashier@campusbookhub.test</span> / <span class="mono">password</span>.</p>
                </div>
            </div>

            <form action="{{ route('login.attempt') }}" method="POST" class="stack">
                @csrf

                <div class="field">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" value="{{ old('email', 'cashier@campusbookhub.test') }}" required>
                </div>

                <div class="field">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" value="password" required>
                </div>

                <label class="checkline">
                    <input type="checkbox" name="remember" value="1" {{ old('remember') ? 'checked' : '' }}>
                    Keep this device signed in
                </label>

                <div class="inline-actions" style="margin-top: 6px;">
                    <button type="submit" class="btn btn-primary">Log In</button>
                    <a href="{{ route('register') }}" class="btn btn-secondary">Create new account</a>
                </div>
            </form>
        </section>
    </div>
@endsection