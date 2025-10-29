@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Invoice History</h2>

    <form method="GET" action="{{ route('invoices.index') }}" class="row g-3 mb-4">
        <div class="col-md-3">
            <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                placeholder="Search invoice number or customer...">
        </div>
        <div class="col-md-2">
            <input type="date" name="from_date" value="{{ request('from_date') }}" class="form-control">
        </div>
        <div class="col-md-2">
            <input type="date" name="to_date" value="{{ request('to_date') }}" class="form-control">
        </div>
        <div class="col-md-2">
            <select name="status" class="form-select">
                <option value="">All Status</option>
                <option value="draft" {{ request('status')=='draft'?'selected':'' }}>Draft</option>
                <option value="issued" {{ request('status')=='issued'?'selected':'' }}>Issued</option>
                <option value="cancelled" {{ request('status')=='cancelled'?'selected':'' }}>Cancelled</option>
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100">Filter</button>
        </div>
    </form>

    <table class="table table-bordered align-middle">
        <thead class="table-light">
            <tr>
                <th>Invoice #</th>
                <th>Date</th>
                <th>Customer</th>
                <th>Status</th>
                <th class="text-end">Total (₹)</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($invoices as $invoice)
                <tr>
                    <td>{{ $invoice->invoice_number }}</td>
                    <td>{{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d M Y') }}</td>
                    <td>
                        @if($invoice->order && $invoice->order->customer)
                            {{ $invoice->order->customer->first_name }} {{ $invoice->order->customer->last_name }}
                        @else
                            <em>—</em>
                        @endif
                    </td>
                    <td>
                        <span class="badge bg-{{ 
                            $invoice->status === 'issued' ? 'success' : 
                            ($invoice->status === 'draft' ? 'secondary' : 'danger') 
                        }}">
                            {{ ucfirst($invoice->status) }}
                        </span>
                    </td>
                    <td class="text-end">{{ number_format($invoice->total, 2) }}</td>
                    <td>
                        <a href="{{ route('invoices.show', $invoice->id) }}" class="btn btn-sm btn-info">View</a>
                        <a href="{{ route('invoices.print', $invoice->id) }}" class="btn btn-sm btn-outline-secondary">Print</a>
                        <a href="{{ route('invoices.download', $invoice->id) }}" class="btn btn-sm btn-success">Download</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center text-muted">No invoices found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="d-flex justify-content-center">
        {{ $invoices->links() }}
    </div>
</div>
@endsection
