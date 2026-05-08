@extends('layouts.app')

@section('title', 'Receipt Preview')

@section('content')
    @php
        $buyerName = $invoice->customer?->name ?? 'Walk-in Buyer';
        $buyerAddress = $invoice->customer?->address ?? 'Retail Counter Sale';
    @endphp

    <div class="page-head">
        <div>
            <span class="badge">Generated Receipt</span>
            <h1 class="page-title" style="margin-top: 12px;">Thermal invoice preview</h1>
            <p class="page-subtitle">This preview is tightened for an 80mm receipt style and matches the National Book Store - Ventic Branch branding used throughout the project.</p>
        </div>

        <div class="inline-actions screen-only">
            <button type="button" onclick="window.print()" class="btn btn-primary">Print Receipt</button>
            <a href="{{ route('invoices.pdf', $invoice) }}" class="btn btn-secondary">Download PDF</a>
            @if (auth()->user()->is_admin)
                <a href="{{ route('invoices.edit', $invoice) }}" class="btn btn-ghost">Edit</a>
            @endif
            <a href="{{ route('invoices.index') }}" class="btn btn-ghost">Back to history</a>
        </div>
    </div>

    <div class="grid-2">
        <section class="panel">
            <span class="badge">Transaction Summary</span>
            <div class="table-wrap" style="margin-top: 18px;">
                <table>
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th>Qty</th>
                            <th>Price</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($invoice->items as $item)
                            <tr>
                                <td>
                                    <div class="stack">
                                        <strong>{{ $item->item_name }}</strong>
                                        <span class="muted">{{ $item->product?->category ?? 'General Merchandise' }}</span>
                                    </div>
                                </td>
                                <td class="mono">{{ $item->quantity }}</td>
                                <td class="mono">{{ number_format((float) $item->price, 2) }}</td>
                                <td class="mono">{{ number_format((float) $item->amount, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="stats-grid" style="grid-template-columns: repeat(2, minmax(0, 1fr)); margin-top: 20px; margin-bottom: 0;">
                <article class="stat-card">
                    <span class="label">Buyer</span>
                    <strong class="stat-value" style="font-size: 24px;">{{ $buyerName }}</strong>
                    <span class="stat-note">{{ $buyerAddress }}</span>
                </article>
                <article class="stat-card">
                    <span class="label">Tendered Cash</span>
                    <strong class="stat-value mono" style="font-size: 24px;">{{ number_format((float) $invoice->cash, 2) }}</strong>
                    <span class="stat-note">Change: {{ number_format((float) $invoice->change, 2) }}</span>
                </article>
            </div>
        </section>

        <aside class="receipt-wrap">
            <div>
                <div class="receipt print-area">
                    @include('invoices._receipt', ['invoice' => $invoice])
                </div>
                <div class="print-note screen-only">Print from this page to get the clean receipt version.</div>
            </div>
        </aside>
    </div>
@endsection