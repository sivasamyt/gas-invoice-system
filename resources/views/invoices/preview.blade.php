@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h4>Invoice #{{ $invoice->invoice_number }}</h4>
        </div>
        <div class="card-body">
            <p><strong>Date:</strong> {{ $invoice->invoice_date->format('d M Y') }}</p>
            <p><strong>Customer:</strong> {{ $invoice->order->customer->first_name }} {{ $invoice->order->customer->last_name }}</p>
            
            <table class="table table-bordered mt-3">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Rate (₹)</th>
                        <th>Qty (L)</th>
                        <th>Subtotal (₹)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($invoice->items as $item)
                        <tr>
                            <td>{{ $item->product_name }}</td>
                            <td>{{ number_format($item->unit_price, 2) }}</td>
                            <td>{{ $item->quantity_liters }}</td>
                            <td>{{ number_format($item->line_total, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="text-end mt-3">
                <p>Subtotal: ₹{{ number_format($invoice->sub_total, 2) }}</p>
                <p>GST ({{ $invoice->tax_rate }}%): ₹{{ number_format($invoice->tax_amount, 2) }}</p>
                <h5>Total: ₹{{ number_format($invoice->total, 2) }}</h5>
            </div>

            <div class="mt-4 text-center">
                <form method="POST" action="{{ route('invoice.issue', $invoice->id) }}">
                    @csrf
                    <button type="submit" class="btn btn-success">Generate Final Invoice</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
