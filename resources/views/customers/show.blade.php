@extends('layouts.app')

@section('title', 'Customer Details')

@section('content')
    <div class="page-head">
        <div>
            <span class="badge">Customer Profile</span>
            <h1 class="page-title" style="margin-top: 12px;">{{ $customer->name }}</h1>
            <p class="page-subtitle">Receipt-ready customer details plus a quick view of linked transactions.</p>
        </div>

        <div class="inline-actions screen-only">
            <a href="{{ route('customers.edit', $customer) }}" class="btn btn-primary">Edit Profile</a>
            <a href="{{ route('customers.index') }}" class="btn btn-secondary">Back to customers</a>
        </div>
    </div>

    <div class="grid-2">
        <section class="panel">
            <div class="stats-grid" style="grid-template-columns: 1fr; margin-bottom: 0;">
                <article class="stat-card">
                    <span class="label">Address</span>
                    <strong class="stat-value" style="font-size: 24px;">{{ $customer->address }}</strong>
                </article>
                <article class="stat-card">
                    <span class="label">Contact Number</span>
                    <strong class="stat-value mono" style="font-size: 24px;">{{ $customer->contact_number }}</strong>
                </article>
            </div>
        </section>

        <section class="panel">
            <span class="label">Recent Receipts</span>
            @if ($customer->invoices->isEmpty())
                <div class="empty-state" style="margin-top: 12px;">No receipts linked to this customer yet.</div>
            @else
                <div class="table-wrap" style="margin-top: 12px;">
                    <table>
                        <thead>
                            <tr>
                                <th>Transaction</th>
                                <th>Date</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($customer->invoices as $invoice)
                                <tr>
                                    <td><a href="{{ route('invoices.show', $invoice) }}" class="mono">{{ $invoice->trx_no }}</a></td>
                                    <td>{{ $invoice->invoice_date->format('M d, Y h:i A') }}</td>
                                    <td class="mono">{{ number_format((float) $invoice->total_sales, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </section>
    </div>
@endsection
