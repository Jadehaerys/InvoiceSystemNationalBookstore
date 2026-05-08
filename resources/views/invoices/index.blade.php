@extends('layouts.app')

@section('title', 'Receipt History')

@section('content')
    <div class="page-head">
        <div>
            <span class="badge">{{ $isAdmin ? 'Sales Dashboard' : 'My Receipts' }}</span>
            <h1 class="page-title" style="margin-top: 12px;">{{ $isAdmin ? 'Receipt history and daily totals' : 'My purchase history' }}</h1>
            <p class="page-subtitle">{{ $isAdmin ? 'Track completed sales, manage receipts, and monitor the branch totals from one screen.' : 'Review your purchases, reopen any receipt, and export a PDF copy when needed.' }}</p>
        </div>

        <div class="inline-actions screen-only">
            <a href="{{ route('pos.create') }}" class="btn btn-primary">{{ $isAdmin ? 'New Sale' : 'Buy Again' }}</a>
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
                                        <a href="{{ route('invoices.pdf', $invoice) }}" class="btn btn-ghost">PDF</a>
                                        @if ($isAdmin)
                                            <a href="{{ route('invoices.edit', $invoice) }}" class="btn btn-ghost">Edit</a>
                                            <form action="{{ route('invoices.destroy', $invoice) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger" onclick="return confirm('Delete this receipt? Stock will be restored.')">Delete</button>
                                            </form>
                                        @endif
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