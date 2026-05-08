@php
    $buyerName = $invoice->customer?->name ?? 'Walk-in Buyer';
    $buyerAddress = $invoice->customer?->address ?? 'Retail Counter Sale';
    $buyerContact = $invoice->customer?->contact_number ?? 'N/A';
@endphp

<div class="receipt-center">
    <div class="receipt-title">National Book Store</div>
    <div>Ventic Branch</div>
    <div>Liloan, Cebu</div>
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
    Items may be exchanged within 7 days if unused and accompanied by this receipt. Clearance items are considered final sale. This document is generated for an academic Laravel POS project modeled after the submitted National Book Store sample invoice.
</div>