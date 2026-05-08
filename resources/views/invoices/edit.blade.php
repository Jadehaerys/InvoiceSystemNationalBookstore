@extends('layouts.app')

@section('title', 'Edit Receipt')

@section('content')
    <div class="page-head">
        <div>
            <span class="badge">Receipt Revision</span>
            <h1 class="page-title" style="margin-top: 12px;">Edit transaction {{ $invoice->trx_no }}</h1>
            <p class="page-subtitle">Adjust the item list, tendered cash, or buyer assignment. Stock levels will be recalculated automatically when you save.</p>
        </div>

        <div class="inline-actions screen-only">
            <a href="{{ route('invoices.show', $invoice) }}" class="btn btn-secondary">Back to receipt</a>
        </div>
    </div>

    @include('invoices._form', [
        'formAction' => route('invoices.update', $invoice),
        'formMethod' => 'PUT',
        'submitLabel' => 'Save Changes',
        'cancelRoute' => route('invoices.show', $invoice),
    ])
@endsection