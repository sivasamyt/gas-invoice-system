@extends('layouts.app')

@section('content')
    <div class="container py-5">
        <div class="card shadow-lg">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Delivery Order Form</h4>
            </div>
            <div class="card-body">
                <form id="deliveryForm" action="{{ route('order.create') }}" method="POST">
                    @csrf
                    <!-- Step Indicators -->
                    <div class="text-center mb-4">
                        <span class="badge bg-primary step-indicator" id="step1-indicator">Step 1: Customer</span>
                        <span class="badge bg-secondary step-indicator" id="step2-indicator">Step 2: Products</span>
                        <span class="badge bg-secondary step-indicator" id="step3-indicator">Step 3: Address</span>
                        <span class="badge bg-secondary step-indicator" id="step4-indicator">Step 4: Confirm</span>
                    </div>

                    <!-- Step 1: Customer Info -->
                    <div class="form-step" id="step1">
                        <!-- First Name -->
                        <div class="mb-3">
                            <label for="customer_first_name" class="form-label">First Name</label>
                            <input type="text" class="form-control" id="customer_first_name" name="customer_first_name" placeholder="Enter first name" autocomplete="off" required>
                        </div>

                        <!-- Last Name -->
                        <div class="mb-3">
                            <label for="customer_last_name" class="form-label">Last Name</label>
                            <input type="text" class="form-control" id="customer_last_name" name="customer_last_name" placeholder="Enter last name" autocomplete="off" required>
                        </div>

                        <!-- Email -->
                        <div class="mb-3">
                            <label for="customer_email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="customer_email" name="customer_email" placeholder="Enter email address" autocomplete="off">
                        </div>

                        <!-- Mobile Number -->
                        <div class="mb-3">
                            <label for="customer_mobile" class="form-label">Mobile Number</label>
                            <input type="text" class="form-control" id="customer_mobile" name="customer_mobile" placeholder="Enter mobile number" maxlength="10" autocomplete="off" required>
                        </div>

                        <!-- Search Suggestion Box (optional autocomplete for existing customers) -->
                        <div id="customerList" class="list-group position-absolute w-50"></div>

                        <button type="button" class="btn btn-primary next-step">Next</button>
                    </div>
                    <!-- Step 2: Company & Product Selection -->
                    <div class="form-step d-none" id="step2">
                        <div class="mb-3">
                            <label for="company_id" class="form-label">Select Company</label>
                            <select id="company_id" name="company_id" class="form-select">
                                <option value="">-- Choose a Company --</option>
                                @foreach($companies as $company)
                                    <option value="{{ $company['id'] }}">{{ $company['name'] }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Select Products</label>
                            <div id="productList" class="border rounded p-3 bg-light">
                                <p class="text-muted mb-0">Select a company to view available products.</p>
                            </div>
                        </div>

                        <button type="button" class="btn btn-secondary prev-step">Back</button>
                        <button type="button" class="btn btn-primary next-step">Next</button>
                    </div>
                    <!-- Step 3: Address -->
                    <div class="form-step d-none" id="step3">
                        <h5>Billing Address</h5>

                        <div class="mb-3">
                            <label for="billing_address" class="form-label">Address</label>
                            <textarea class="form-control" id="billing_address" name="billing_address" rows="2" placeholder="Enter billing address" required></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="billing_city" class="form-label">City</label>
                                <input type="text" class="form-control" id="billing_city" name="billing_city" placeholder="Enter city" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="billing_state" class="form-label">State</label>
                                <input type="text" class="form-control" id="billing_state" name="billing_state" placeholder="Enter state" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="billing_postcode" class="form-label">Postcode</label>
                                <input type="text" class="form-control" id="billing_postcode" name="billing_postcode" placeholder="Enter postcode" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="billing_country" class="form-label">Country</label>
                                <input type="text" class="form-control" id="billing_country" name="billing_country" placeholder="Enter country" required>
                            </div>
                        </div>

                        <div class="form-check mb-3">
                            <input type="checkbox" class="form-check-input" name= "sameAddress" id="sameAddress" checked>
                            <label class="form-check-label" for="sameAddress">Delivery address same as billing</label>
                        </div>

                        <div id="deliveryAddressSection" class="mb-3 d-none">
                            <h5>Delivery Address</h5>

                            <div class="mb-3">
                                <label for="delivery_address" class="form-label">Address</label>
                                <textarea class="form-control" id="delivery_address" name="delivery_address" rows="2" placeholder="Enter delivery address"></textarea>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="delivery_city" class="form-label">City</label>
                                    <input type="text" class="form-control" id="delivery_city" name="delivery_city" placeholder="Enter city">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="delivery_state" class="form-label">State</label>
                                    <input type="text" class="form-control" id="delivery_state" name="delivery_state" placeholder="Enter state">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="delivery_postcode" class="form-label">Postcode</label>
                                    <input type="text" class="form-control" id="delivery_postcode" name="delivery_postcode" placeholder="Enter postcode">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="delivery_country" class="form-label">Country</label>
                                    <input type="text" class="form-control" id="delivery_country" name="delivery_country" placeholder="Enter country">
                                </div>
                            </div>
                        </div>

                        <button type="button" class="btn btn-secondary prev-step">Back</button>
                        <button type="button" class="btn btn-primary next-step">Next</button>
                    </div>
                    <!-- Step 4: Confirmation -->
                    <div class="form-step d-none" id="step4">
                        <h5>Review Your Order</h5>
                        <ul id="orderSummary" class="list-group mb-3"></ul>
                        <button type="button" class="btn btn-secondary prev-step">Back</button>
                        <button type="submit" class="btn btn-success">Submit Order</button>
                    </div>
                </form>

                <!-- Confirmation Message -->
                {{-- <div id="confirmationMessage" class="alert alert-success mt-4 d-none text-center">
                    ✅ Your order has been successfully placed!  
                    <br> Thank you for choosing us.
                </div> --}}
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const steps = document.querySelectorAll('.form-step');
    const indicators = document.querySelectorAll('.step-indicator');
    let currentStep = 0;
    // form.addEventListener('keydown', function(e) {
    //     if (e.key === 'Enter') {
    //         e.preventDefault();
    //         return false;
    //     }
    // });
    function showStep(index) {
        steps.forEach((step, i) => step.classList.toggle('d-none', i !== index));
        indicators.forEach((badge, i) => {
            badge.classList.toggle('bg-primary', i === index);
            badge.classList.toggle('bg-secondary', i !== index);
        });
    }

    document.querySelectorAll('.next-step').forEach(btn => {
        btn.addEventListener('click', function () {
            if (validateStep(currentStep)) {
                currentStep++;
                showStep(currentStep);
            }
        });
    });

    document.querySelectorAll('.prev-step').forEach(btn => {
        btn.addEventListener('click', function () {
            currentStep--;
            showStep(currentStep);
        });
    });

    document.getElementById('sameAddress').addEventListener('change', function () {
        document.getElementById('deliveryAddressSection').classList.toggle('d-none', this.checked);
    });

    // 🔹 Fetch products when a company is selected
    const companyProducts = @json($companies);
    const companySelect = document.getElementById('company_id');
    const productListDiv = document.getElementById('productList');
    companySelect.addEventListener('change', function () {
        const selectedCompanyId = parseInt(this.value);
        const selectedCompany = companyProducts.find(c => c.id === selectedCompanyId);

        productListDiv.innerHTML = '';

        if (!selectedCompany) {
            productListDiv.innerHTML = `
                <p class="text-muted mb-0">Select a company to view available products.</p>
            `;
            return;
        }
        // Build HTML for products dynamically
        let html = '';        
        selectedCompany.products.forEach(product => {
            html += `
                <div class="row align-items-center mb-2">
                    <div class="col-md-6">
                        <label>${product.name}</label>
                    </div>
                    <div class="col-md-3 d-flex gap-2">
                        <input type="number" class="form-control product-qty" 
                            placeholder="Qty" name="products[${product.id}]" />
                        <span>liters</span>
                    </div>
                </div>
            `;
            
        });
        productListDiv.innerHTML = html;
    });

    // ✅ Step Validation
    function validateStep(step) {
        let valid = true;
        if (step === 0) {
            const firstName = document.getElementById('customer_first_name');
            const lastName = document.getElementById('customer_last_name');
            const email = document.getElementById('customer_email');
            const mobile = document.getElementById('customer_mobile');
            [firstName, lastName, email, mobile].forEach(input => input.classList.remove('is-invalid'));
            // Validate first name
            if (!firstName.value.trim()) {
                firstName.classList.add('is-invalid');
                valid = false;
            }
            // Validate last name
            if (!lastName.value.trim()) {
                lastName.classList.add('is-invalid');
                valid = false;
            }
            // Validate email format
            if (!email.value.trim() || !email.value.includes('@') || !email.value.includes('.')) {
                email.classList.add('is-invalid');
                valid = false;
            }
            // Validate mobile (10-digit number)
            const mobilePattern = /^[0-9]{10}$/;
            if (!mobilePattern.test(mobile.value.trim())) {
                mobile.classList.add('is-invalid');
                valid = false;
            }
            if (!valid) return;
        }
        if (step === 1) {
            const qtyInputs = document.querySelectorAll('.product-qty');
            valid = Array.from(qtyInputs).some(input => input.value > 0);
            if (!valid) alert('Please enter at least one product quantity.');
        }
        if (step === 2) {
            const billingFields = [
                'billing_address',
                'billing_city',
                'billing_state',
                'billing_postcode',
                'billing_country'
            ];

            billingFields.forEach(id => {
                const el = document.getElementById(id);
                if (!el.value.trim()) {
                    el.classList.add('is-invalid');
                    valid = false;
                } else {
                    el.classList.remove('is-invalid');
                }
            });

            const sameAddress = document.getElementById('sameAddress').checked;

            if (!sameAddress) {
                const deliveryFields = [
                    'delivery_address',
                    'delivery_city',
                    'delivery_state',
                    'delivery_postcode',
                    'delivery_country'
                ];

                deliveryFields.forEach(id => {
                    const el = document.getElementById(id);
                    if (!el.value.trim()) {
                        el.classList.add('is-invalid');
                        valid = false;
                    } else {
                        el.classList.remove('is-invalid');
                    }
                });
            }
        }
        return valid;
    }
});
</script>
@endpush
