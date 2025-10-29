<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice - {{ $invoice->invoice_number }}</title>
    <style>
        /* ---------- Base Layout ---------- */
        body {
            font-family: sans-serif;
            font-size: 12px;
            color: #333;
            margin: 0;
            padding: 20px;
        }

        h1, h2, h3 {
            margin: 0;
        }

        .header {
            text-align: center;
            margin-bottom: 25px;
            border-bottom: 2px solid #222;
            padding-bottom: 10px;
        }

        .company-info {
            text-align: right;
            font-size: 11px;
            color: #555;
        }

        /* ---------- Table Styling ---------- */
        table.invoice-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 12px;
        }

        table.invoice-table th,
        table.invoice-table td {
            border: 1px solid #999;
            padding: 8px;
        }

        table.invoice-table th {
            background-color: #f4f4f4;
            text-align: left;
            font-weight: bold;
        }

        table.invoice-table tr:nth-child(even) {
            background-color: #fafafa;
        }

        table.invoice-table td:last-child,
        table.invoice-table th:last-child {
            text-align: right;
        }

        /* ---------- Totals ---------- */
        .totals {
            margin-top: 25px;
            text-align: right;
            font-size: 13px;
        }

        .totals p {
            margin: 3px 0;
        }

        .totals h3 {
            margin-top: 8px;
            border-top: 1px solid #999;
            padding-top: 5px;
        }

        /* ---------- Footer ---------- */
        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 11px;
            color: #666;
        }

        /* ---------- Print Optimization ---------- */
        @media print {
            body {
                margin: 10mm;
                font-size: 11px;
                color: #000;
            }

            .no-print {
                display: none !important;
            }

            .header {
                border-bottom: 2px solid #000;
                padding-bottom: 8px;
            }

            .invoice-table th,
            .invoice-table td {
                border: 1px solid #000 !important;
            }

            .totals {
                page-break-inside: avoid;
            }

            @page {
                size: A4 portrait;
                margin: 15mm;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Invoice</h1>
        <h3>{{ $invoice->invoice_number }}</h3>
        <p>Date: {{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d M Y') }}</p>
    </div>

    <div class="company-info">
       <strong>{{ $invoice->order->company->name }}</strong><br>
        {{ $invoice->order->company->address }}<br>
        Email: {{ $invoice->order->company->email_address }}<br>
        BN Number: {{ $invoice->order->company->bn_number }}
    </div>

    <h3>Customer Details</h3>
    <p>
        <strong>{{ $invoice->order->customer->first_name }} {{ $invoice->order->customer->last_name }}</strong><br>
        {{ $invoice->order->customer->email_address }}<br>
        {{ $invoice->order->customer->mobile_number }}
    </p>

    <table class="invoice-table">
        <thead>
            <tr>
                <th style="width: 5%;">#</th>
                <th style="width: 45%;">Product</th>
                <th style="width: 15%; text-align:right;">Qty (Liters)</th>
                <th style="width: 15%; text-align:right;">Rate (₹)</th>
                <th style="width: 20%; text-align:right;">Subtotal (₹)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoice->items as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->product_name }}</td>
                    <td style="text-align:right;">{{ $item->quantity_liters }}</td>
                    <td style="text-align:right;">{{ number_format($item->unit_price, 2) }}</td>
                    <td style="text-align:right;">{{ number_format($item->line_total, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="totals">
        <p>Subtotal: ₹{{ number_format($invoice->sub_total, 2) }}</p>
        <p>GST ({{ $invoice->tax_rate }}%): ₹{{ number_format($invoice->tax_amount, 2) }}</p>
        <h3>Total: ₹{{ number_format($invoice->total, 2) }}</h3>
    </div>

    <div class="footer">
        Thank you for your business!<br>
        This is a computer-generated invoice.
    </div>

    <div class="no-print" style="text-align:center; margin-top:15px;">
        <button onclick="window.print()">🖨️ Print Invoice</button>
    </div>
</body>
</html>
