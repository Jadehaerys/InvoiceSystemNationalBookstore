@extends('layouts.app')

@section('title', 'POS Checkout')

@section('content')
    <div class="page-head">
        <div>
            <span class="badge">POS Checkout</span>
            <h1 class="page-title" style="margin-top: 12px;">Create a new bookstore sale</h1>
            <p class="page-subtitle">Build the cart, accept cash, and generate a thermal receipt layout based on your sample invoice image.</p>
        </div>

        <div class="inline-actions screen-only">
            <a href="{{ route('invoices.index') }}" class="btn btn-secondary">View receipt history</a>
        </div>
    </div>

    @include('invoices._form', [
        'formAction' => route('invoices.store'),
        'formMethod' => 'POST',
        'submitLabel' => 'Generate Receipt',
        'cancelRoute' => route('invoices.index'),
    ])
@endsection