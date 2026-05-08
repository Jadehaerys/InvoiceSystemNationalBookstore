<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Receipt {{ $invoice->trx_no }}</title>
    <style>
        body {
            margin: 0;
            padding: 12px 0;
            font-family: DejaVu Sans Mono, monospace;
            color: #111;
            font-size: 10px;
        }

        .receipt {
            width: 80mm;
            margin: 0 auto;
            padding: 8px 10px 12px;
            border: 1px solid #bbb;
        }

        .receipt-center {
            text-align: center;
        }

        .receipt-title {
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .receipt-rule {
            margin: 6px 0;
            border-top: 1px dashed #666;
        }

        .receipt-row,
        .receipt-meta {
            display: table;
            width: 100%;
        }

        .receipt-row span,
        .receipt-meta span {
            display: table-cell;
        }

        .receipt-row span:last-child,
        .receipt-meta span:last-child {
            text-align: right;
        }

        .receipt-item {
            margin: 6px 0;
        }

        .receipt-item-name {
            text-transform: uppercase;
        }

        .receipt-footer {
            margin-top: 10px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="receipt">
        @include('invoices._receipt', ['invoice' => $invoice])
    </div>
</body>
</html>