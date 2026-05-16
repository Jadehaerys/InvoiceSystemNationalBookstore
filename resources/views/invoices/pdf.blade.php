@php
    $buyerName    = $invoice->customer?->name             ?? 'Walk-in Buyer';
    $buyerAddress = $invoice->customer?->address          ?? 'Retail Counter Sale';
    $buyerContact = $invoice->customer?->contact_number   ?? 'N/A';
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Receipt {{ $invoice->trx_no }}</title>
    <style>
        @page { margin: 10mm 8mm; }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            padding: 0;
            font-family: 'DejaVu Sans Mono', monospace;
            font-size: 9pt;
            line-height: 1.5;
            color: #111;
        }

        .wrap {
            width: 74mm;
            margin: 0 auto;
        }

        .c  { text-align: center; }
        .bold { font-weight: bold; }

        .store-name {
            font-size: 11pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.1em;
        }

        hr {
            border: none;
            border-top: 1px dashed #888;
            margin: 5pt 0;
        }

        /* Two-column rows — use real HTML tables for DOMPDF compatibility */
        .row {
            width: 100%;
            border-collapse: collapse;
            table-layout: auto;
        }
        .row td { padding: 1pt 0; vertical-align: top; }
        .row .v { text-align: right; white-space: nowrap; padding-left: 4pt; }
        .row .trx { font-size: 7.5pt; word-break: break-all; }

        .item-name {
            font-weight: bold;
            text-transform: uppercase;
            margin: 4pt 0 1pt;
        }
        .dim { color: #555; font-size: 8.5pt; }

        .footer {
            margin-top: 8pt;
            font-size: 7.5pt;
            color: #555;
            text-align: center;
            line-height: 1.55;
        }
    </style>
</head>
<body>
<div class="wrap">

    <div class="c">
        <div class="store-name">National Book Store</div>
        <div>Ventic Branch</div>
        <div>Liloan, Cebu</div>
        <div>VAT REG TIN: 000-000-000-000</div>
    </div>

    <hr>
    <div class="c">Serial No.: {{ $invoice->serial_no }}</div>
    <hr>

    <table class="row">
        <tr>
            <td>{{ $invoice->invoice_date->format('m/d/Y') }}</td>
            <td class="v">{{ $invoice->invoice_date->format('H:i:s') }}</td>
        </tr>
        <tr>
            <td style="width:28%">TrxNo:</td>
            <td class="v trx" style="width:72%">{{ $invoice->trx_no }}</td>
        </tr>
        <tr>
            <td>Clerk:</td>
            <td class="v">{{ $invoice->clerk }}</td>
        </tr>
        <tr>
            <td>Term No.:</td>
            <td class="v">{{ $invoice->term_no }}</td>
        </tr>
    </table>

    <hr>

    @foreach ($invoice->items as $item)
        <div class="item-name">{{ $item->quantity }} {{ $item->item_name }}</div>
        <table class="row">
            <tr>
                <td class="dim">{{ number_format((float) $item->price, 2) }} each</td>
                <td class="v">{{ number_format((float) $item->amount, 2) }}</td>
            </tr>
        </table>
    @endforeach

    <hr>

    <table class="row">
        <tr><td>No. of Items</td><td class="v">{{ $invoice->items->sum('quantity') }}</td></tr>
        <tr><td>Amount Due</td><td class="v">{{ number_format((float) $invoice->amount_due, 2) }}</td></tr>
        <tr><td>Cash</td><td class="v">{{ number_format((float) $invoice->cash, 2) }}</td></tr>
        <tr><td>Change</td><td class="v">{{ number_format((float) $invoice->change, 2) }}</td></tr>
    </table>

    <hr>
    <div class="c">Tax Info</div>

    <table class="row">
        <tr><td>Non-Vatable</td><td class="v">0.00</td></tr>
        <tr><td>VATable</td><td class="v">{{ number_format((float) $invoice->vat_sales, 2) }}</td></tr>
        <tr><td>VAT Zero-Rated Sale</td><td class="v">0.00</td></tr>
        <tr><td>VAT Exempt Sale</td><td class="v">0.00</td></tr>
        <tr><td>VAT(12%)</td><td class="v">{{ number_format((float) $invoice->vat, 2) }}</td></tr>
        <tr><td>Total Sales</td><td class="v">{{ number_format((float) $invoice->total_sales, 2) }}</td></tr>
    </table>

    <hr>

    <div>BUYER'S NAME : {{ $buyerName }}</div>
    <div>ADDRESS&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: {{ $buyerAddress }}</div>
    <div>CONTACT&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: {{ $buyerContact }}</div>

    <div class="footer">
        Items may be exchanged within 7 days if unused and accompanied by
        this receipt. Clearance items are considered final sale.<br>
        This document is generated for an academic Laravel POS project
        modeled after the submitted National Book Store sample invoice.
    </div>

</div>
</body>
</html>