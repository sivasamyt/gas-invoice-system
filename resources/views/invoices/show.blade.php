@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Invoice #{{ $invoice->invoice_number }}</h2>
    <p><strong>Date:</strong> {{ $invoice->invoice_date->format('d M Y') }}</p>
    <p><strong>Status:</strong> {{ ucfirst($invoice->status) }}</p>
    <p><strong>Customer:</strong> {{ $invoice->order->customer->first_name }} {{ $invoice->order->customer->last_name }}</p>
    
    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>Product</th>
                <th>Price per Liter</th>
                <th>Qty</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($invoice->items as $item)
                <tr>
                    <td>{{ $item->product_name ?? 'N/A' }}</td>
                    <td>{{ number_format($item->unit_price, 2) }}</td>
                    <td>{{ $item->quantity_liters }}</td>
                    <td>{{ number_format($item->line_total, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <p><strong>Subtotal:</strong> ₹{{ number_format($invoice->sub_total, 2) }}</p>
    <p><strong>Tax ({{ $invoice->tax_rate }}%):</strong> ₹{{ number_format($invoice->tax_amount, 2) }}</p>
    <p><strong>Total:</strong> ₹{{ number_format($invoice->total, 2) }}</p>
</div>
@endsection
