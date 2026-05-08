@extends('layouts.app')

@section('title', 'Receipt History')

@section('content')
    <div class="page-head">
        <div>
            <span class="badge">Sales Dashboard</span>
            <h1 class="page-title" style="margin-top: 12px;">Receipt history and daily totals</h1>
            <p class="page-subtitle">Track completed sales, jump back into any receipt, and keep the final project flow close to a bookstore cashier terminal.</p>
        </div>

        <div class="inline-actions screen-only">
            <a href="{{ route('pos.create') }}" class="btn btn-primary">New Sale</a>
        </div>
    </div>

    <section class="stats-grid">
        <article class="stat-card">
            <span class="label">Transactions</span>
            <strong class="stat-value">{{ $summary['transactions'] }}</strong>
            <span class="stat-note">Completed receipts in the system</span>
        </article>
        <article class="stat-card">
            <span class="label">Sales Today</span>
            <strong class="stat-value mono">{{ number_format($summary['sales_today'], 2) }}</strong>
            <span class="stat-note">Same-day total based on receipt timestamps</span>
        </article>
        <article class="stat-card">
            <span class="label">Revenue</span>
            <strong class="stat-value mono">{{ number_format($summary['revenue'], 2) }}</strong>
            <span class="stat-note">Cumulative recorded sales</span>
        </article>
    </section>

    <section class="panel">
        @if ($invoices->isEmpty())
            <div class="empty-state">
                No receipts yet. Create your first bookstore sale from the POS screen.
                <div class="inline-actions" style="margin-top: 14px;">
                    <a href="{{ route('pos.create') }}" class="btn btn-primary">Open POS</a>
                </div>
            </div>
        @else
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Transaction</th>
                            <th>Buyer</th>
                            <th>Date</th>
                            <th>Items</th>
                            <th>Amount Due</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($invoices as $invoice)
                            <tr>
                                <td>
                                    <div class="stack">
                                        <strong class="mono">{{ $invoice->trx_no }}</strong>
                                        <span class="muted mono">{{ $invoice->serial_no }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="stack">
                                        <strong>{{ $invoice->customer?->name ?? 'Walk-in Buyer' }}</strong>
                                        <span class="muted">Clerk: {{ $invoice->clerk }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="stack">
                                        <strong>{{ $invoice->invoice_date->format('M d, Y') }}</strong>
                                        <span class="muted mono">{{ $invoice->invoice_date->format('h:i:s A') }}</span>
                                    </div>
                                </td>
                                <td class="mono">{{ $invoice->items->sum('quantity') }}</td>
                                <td class="mono">{{ number_format((float) $invoice->amount_due, 2) }}</td>
                                <td>
                                    <div class="table-actions">
                                        <a href="{{ route('invoices.show', $invoice) }}" class="btn btn-secondary">View</a>
                                        <a href="{{ route('invoices.edit', $invoice) }}" class="btn btn-ghost">Edit</a>
                                        <form action="{{ route('invoices.destroy', $invoice) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger" onclick="return confirm('Delete this receipt?')">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </section>
@endsection