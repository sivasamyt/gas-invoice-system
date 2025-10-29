
@extends('layouts.app')

@section('content')
 <div class="container mt-5">
        <h1 class="mb-4">Welcome</h1>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

           <div class="d-flex gap-3">
        <a href="{{ route('order.form') }}" class="btn btn-primary">
            🧾 Place New Order
        </a>

        <a href="{{ route('invoices.index') }}" class="btn btn-outline-secondary">
            📄 View Invoice History
        </a>
    </div>
    </div>
@endsection