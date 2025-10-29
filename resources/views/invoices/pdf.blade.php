<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice - {{ $invoice->invoice_number }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; }
        h1, h2, h3 { margin: 0; }
        .header { text-align: center; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #999; padding: 6px; text-align: left; }
        th { background-color: #f2f2f2; }
        .totals { text-align: right; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Invoice</h1>
        <h3>{{ $invoice->invoice_number }}</h3>
        <p>Date: {{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d M Y') }}</p>
    </div>

    <h3>Customer Details</h3>
    <p>
        <strong>{{ $invoice->order->customer->first_name }} {{ $invoice->order->customer->last_name }}</strong><br>
        {{ $invoice->order->customer->email_address }}<br>
        {{ $invoice->order->customer->mobile_number }}
    </p>

    <h3>Order Items</h3>
    <table>
        <thead>
            <tr>
                <th>Product</th>
                <th>Qty (Liters)</th>
                <th>Rate (₹)</th>
                <th>Subtotal (₹)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoice->items as $item)
                <tr>
                    <td>{{ $item->product_name }}</td>
                    <td>{{ $item->quantity_liters }}</td>
                    <td>{{ number_format($item->unit_price, 2) }}</td>
                    <td>{{ number_format($item->line_total, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="totals">
        <p>Subtotal: ₹{{ number_format($invoice->sub_total, 2) }}</p>
        <p>GST ({{ $invoice->tax_rate }}%): ₹{{ number_format($invoice->tax_amount, 2) }}</p>
        <h3>Total: ₹{{ number_format($invoice->total, 2) }}</h3>
    </div>

    <p style="text-align:center; margin-top:30px;">Thank you for your business!</p>
</body>
</html>
