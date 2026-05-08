@extends('layouts.app')

@section('title', 'Receipt Preview')

@section('content')
    @php
        $buyerName = $invoice->customer?->name ?? 'Walk-in Buyer';
        $buyerAddress = $invoice->customer?->address ?? 'Retail Counter Sale';
        $buyerContact = $invoice->customer?->contact_number ?? 'N/A';
    @endphp

    <div class="page-head">
        <div>
            <span class="badge">Generated Receipt</span>
            <h1 class="page-title" style="margin-top: 12px;">Thermal invoice preview</h1>
            <p class="page-subtitle">This layout mirrors the narrow National Book Store style: store header, line items, VAT block, tendered cash, and a long policy footer.</p>
        </div>

        <div class="inline-actions screen-only">
            <button type="button" onclick="window.print()" class="btn btn-primary">Print Receipt</button>
            <a href="{{ route('invoices.edit', $invoice) }}" class="btn btn-secondary">Edit</a>
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
                    <div class="receipt-center">
                        <div class="receipt-title">Campus Book Hub</div>
                        <div>Bookstore and Study Supply Center</div>
                        <div>Level 2, Learning Arcade, Cebu</div>
                        <div>VAT REG TIN: 000-000-000-000</div>
                    </div>

                    <div class="receipt-rule"></div>

                    <div class="receipt-center">Serial No.: {{ $invoice->serial_no }}</div>
                    <div class="receipt-rule"></div>

                    <div class="receipt-row"><span>{{ $invoice->invoice_date->format('m/d/Y') }}</span><span>{{ $invoice->invoice_date->format('H:i:s') }}</span></div>
                    <div class="receipt-row"><span>TrxNo:</span><span>{{ $invoice->trx_no }}</span></div>
                    <div class="receipt-row"><span>Clerk:</span><span>{{ $invoice->clerk }}</span></div>
                    <div class="receipt-row"><span>Term No.:</span><span>{{ $invoice->term_no }}</span></div>

                    <div class="receipt-rule"></div>

                    @foreach ($invoice->items as $item)
                        <div class="receipt-item">
                            <div class="receipt-item-name">{{ $item->quantity }} {{ $item->item_name }}</div>
                            <div class="receipt-meta">
                                <span>{{ number_format((float) $item->price, 2) }} each</span>
                                <span>{{ number_format((float) $item->amount, 2) }}</span>
                            </div>
                        </div>
                    @endforeach

                    <div class="receipt-rule"></div>

                    <div class="receipt-row"><span>No. of Items</span><span>{{ $invoice->items->sum('quantity') }}</span></div>
                    <div class="receipt-row"><span>Amount Due</span><span>{{ number_format((float) $invoice->amount_due, 2) }}</span></div>
                    <div class="receipt-row"><span>Cash</span><span>{{ number_format((float) $invoice->cash, 2) }}</span></div>
                    <div class="receipt-row"><span>Change</span><span>{{ number_format((float) $invoice->change, 2) }}</span></div>

                    <div class="receipt-rule"></div>

                    <div class="receipt-center">Tax Info</div>
                    <div class="receipt-row"><span>Non-Vatable</span><span>0.00</span></div>
                    <div class="receipt-row"><span>VATable</span><span>{{ number_format((float) $invoice->vat_sales, 2) }}</span></div>
                    <div class="receipt-row"><span>VAT Zero-Rated Sale</span><span>{{ number_format((float) $invoice->vat_zero, 2) }}</span></div>
                    <div class="receipt-row"><span>VAT Exempt Sale</span><span>{{ number_format((float) $invoice->vat_exempt, 2) }}</span></div>
                    <div class="receipt-row"><span>VAT(12%)</span><span>{{ number_format((float) $invoice->vat, 2) }}</span></div>
                    <div class="receipt-row"><span>Total Sales</span><span>{{ number_format((float) $invoice->total_sales, 2) }}</span></div>

                    <div class="receipt-rule"></div>

                    <div>BUYER'S NAME : {{ $buyerName }}</div>
                    <div>ADDRESS      : {{ $buyerAddress }}</div>
                    <div>CONTACT      : {{ $buyerContact }}</div>

                    <div class="receipt-footer">
                        Returns or exchanges are allowed within 7 days from purchase if the item is unused and the receipt is presented. School and clearance items are considered final sale. This invoice is generated from an academic POS project and follows a thermal receipt presentation inspired by the submitted sample image.
                    </div>
                </div>
                <div class="print-note screen-only">Print from this page to get the clean receipt version.</div>
            </div>
        </aside>
    </div>
@endsection