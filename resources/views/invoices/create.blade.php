@extends('layouts.app')

@section('title', 'POS Checkout')

@section('content')
    <div class="page-head">
        <div>
            <span class="badge">{{ $isAdmin ? 'POS Checkout' : 'Customer Checkout' }}</span>
            <h1 class="page-title" style="margin-top: 12px;">{{ $isAdmin ? 'Create a new sale' : 'Buy books and get your invoice' }}</h1>
            <p class="page-subtitle">{{ $isAdmin ? 'Build the cart, accept cash, and generate a receipt in the National Book Store - Ventic Branch format.' : 'Your account is already linked to your customer profile, so your purchase will generate a receipt under your name automatically.' }}</p>
        </div>

        <div class="inline-actions screen-only">
            <a href="{{ route('invoices.index') }}" class="btn btn-secondary">{{ $isAdmin ? 'View receipt history' : 'View my receipts' }}</a>
        </div>
    </div>

    @include('invoices._form', [
        'formAction' => route('invoices.store'),
        'formMethod' => 'POST',
        'submitLabel' => $isAdmin ? 'Generate Receipt' : 'Place Order',
        'cancelRoute' => route('invoices.index'),
    ])
@endsection