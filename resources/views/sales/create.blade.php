@extends('layouts.app')

@section('title', 'Create Sale')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="bi bi-plus-lg"></i> Create Sale</h4>
    <a href="{{ route('sales.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Back to Sales
    </a>
</div>

<form method="POST" action="{{ route('sales.store') }}" id="saleForm">
    @csrf
    <div class="row g-4">
        <!-- Sale Info -->
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="bi bi-cart-plus"></i> Sale Items</h5>
                </div>
                <div class="card-body">
                    <!-- Customer Selection -->
                    <div class="mb-4">
                        <label for="customer_id" class="form-label">Customer <span class="text-danger">*</span></label>
                        <select class="form-select @error('customer_id') is-invalid @enderror" id="customer_id" name="customer_id" required>
                            <option value="">-- Select Customer --</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                    {{ $customer->name }} ({{ $customer->email }})
                                </option>
                            @endforeach
                        </select>
                        @error('customer_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Product Rows -->
                    <div class="table-responsive">
                        <table class="table" id="itemsTable">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 40%">Product</th>
                                    <th style="width: 15%">Price</th>
                                    <th style="width: 15%">Quantity</th>
                                    <th style="width: 15%">Subtotal</th>
                                    <th style="width: 15%" class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody id="itemsBody">
                                <tr class="item-row" data-index="0">
                                    <td>
                                        <select class="form-select product-select" name="items[0][product_id]" required>
                                            <option value="">-- Select Product --</option>
                                            @foreach($products as $product)
                                                <option value="{{ $product->id }}" data-price="{{ $product->price }}" data-stock="{{ $product->stock_quantity }}">
                                                    {{ $product->name }} (Stock: {{ $product->stock_quantity }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control price-display" readonly value="$0.00">
                                        <input type="hidden" name="items[0][price]" class="price-input" value="0">
                                    </td>
                                    <td>
                                        <input type="number" class="form-control quantity-input" name="items[0][quantity]" min="1" value="1" required>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control subtotal-display" readonly value="$0.00">
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-sm btn-outline-danger remove-row" title="Remove">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <button type="button" class="btn btn-outline-primary" id="addRow">
                        <i class="bi bi-plus-lg"></i> Add Product
                    </button>
                </div>
            </div>
        </div>

        <!-- Summary -->
        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="bi bi-calculator"></i> Order Summary</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal:</span>
                        <strong id="subtotalDisplay">$0.00</strong>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between">
                        <span class="fs-5">Grand Total:</span>
                        <h3 class="text-primary" id="grandTotalDisplay">$0.00</h3>
                    </div>
                    <input type="hidden" name="total_amount" id="totalAmountInput" value="0">
                </div>
                <div class="card-footer bg-white">
                    <button type="submit" class="btn btn-primary w-100" id="submitBtn">
                        <i class="bi bi-check-lg"></i> Complete Sale
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let rowIndex = 1;
    const products = @json($products);

    // Add new row
    document.getElementById('addRow').addEventListener('click', function() {
        const tbody = document.getElementById('itemsBody');
        const newRow = document.createElement('tr');
        newRow.className = 'item-row';
        newRow.dataset.index = rowIndex;

        let options = '<option value="">-- Select Product --</option>';
        products.forEach(function(p) {
            options += `<option value="${p.id}" data-price="${p.price}" data-stock="${p.stock_quantity}">${p.name} (Stock: ${p.stock_quantity})</option>`;
        });

        newRow.innerHTML = `
            <td>
                <select class="form-select product-select" name="items[${rowIndex}][product_id]" required>
                    ${options}
                </select>
            </td>
            <td>
                <input type="text" class="form-control price-display" readonly value="$0.00">
                <input type="hidden" name="items[${rowIndex}][price]" class="price-input" value="0">
            </td>
            <td>
                <input type="number" class="form-control quantity-input" name="items[${rowIndex}][quantity]" min="1" value="1" required>
            </td>
            <td>
                <input type="text" class="form-control subtotal-display" readonly value="$0.00">
            </td>
            <td class="text-center">
                <button type="button" class="btn btn-sm btn-outline-danger remove-row" title="Remove">
                    <i class="bi bi-trash"></i>
                </button>
            </td>
        `;

        tbody.appendChild(newRow);
        rowIndex++;
        bindEvents();
        calculateTotal();
    });

    // Remove row
    function bindEvents() {
        document.querySelectorAll('.remove-row').forEach(function(btn) {
            btn.onclick = function() {
                const rows = document.querySelectorAll('.item-row');
                if (rows.length > 1) {
                    this.closest('tr').remove();
                    calculateTotal();
                }
            };
        });

        document.querySelectorAll('.product-select').forEach(function(select) {
            select.onchange = function() {
                const row = this.closest('tr');
                const selectedOption = this.options[this.selectedIndex];
                const price = selectedOption.dataset.price || 0;
                const priceDisplay = row.querySelector('.price-display');
                const priceInput = row.querySelector('.price-input');
                priceDisplay.value = '$' + parseFloat(price).toFixed(2);
                priceInput.value = price;
                calculateRowSubtotal(row);
                calculateTotal();
            };
        });

        document.querySelectorAll('.quantity-input').forEach(function(input) {
            input.oninput = function() {
                const row = this.closest('tr');
                calculateRowSubtotal(row);
                calculateTotal();
            };
        });
    }

    function calculateRowSubtotal(row) {
        const price = parseFloat(row.querySelector('.price-input').value) || 0;
        const quantity = parseInt(row.querySelector('.quantity-input').value) || 0;
        const subtotal = price * quantity;
        row.querySelector('.subtotal-display').value = '$' + subtotal.toFixed(2);
    }

    function calculateTotal() {
        let grandTotal = 0;
        document.querySelectorAll('.item-row').forEach(function(row) {
            const price = parseFloat(row.querySelector('.price-input').value) || 0;
            const quantity = parseInt(row.querySelector('.quantity-input').value) || 0;
            grandTotal += price * quantity;
        });
        document.getElementById('subtotalDisplay').textContent = '$' + grandTotal.toFixed(2);
        document.getElementById('grandTotalDisplay').textContent = '$' + grandTotal.toFixed(2);
        document.getElementById('totalAmountInput').value = grandTotal.toFixed(2);
    }

    bindEvents();
});
</script>
@endpush