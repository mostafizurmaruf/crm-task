@extends('layouts.app')

@section('title', 'Sale Details')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="bi bi-receipt"></i> Sale #{{ $sale->invoice_no }}</h4>
    <a href="{{ route('sales.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Back to Sales
    </a>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bi bi-file-earmark-text"></i> Invoice</h5>
                <span class="badge bg-primary">{{ $sale->invoice_no }}</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped table-hover mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th>Product</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sale->items as $item)
                                <tr>
                                    <td>{{ $item->product->name ?? 'Deleted Product' }}</td>
                                    <td>${{ number_format($item->price, 2) }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td><strong>${{ number_format($item->subtotal, 2) }}</strong></td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="table-dark">
                                <td colspan="3" class="text-end"><strong>Grand Total:</strong></td>
                                <td><strong>${{ number_format($sale->total, 2) }}</strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-info-circle"></i> Sale Information</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <p class="text-muted mb-1">Invoice Number</p>
                    <h5><code>{{ $sale->invoice_no }}</code></h5>
                </div>
                <div class="mb-3">
                    <p class="text-muted mb-1">Date</p>
                    <h5>{{ $sale->created_at->format('M d, Y h:i A') }}</h5>
                </div>
                <div class="mb-3">
                    <p class="text-muted mb-1">Customer</p>
                    <h5><i class="bi bi-person"></i> {{ $sale->customer->name ?? 'N/A' }}</h5>
                    <small class="text-muted">{{ $sale->customer->email ?? '' }}</small>
                </div>
                <div class="mb-3">
                    <p class="text-muted mb-1">Employee</p>
                    <h5><i class="bi bi-person-badge"></i> {{ $sale->employee->name ?? 'N/A' }}</h5>
                </div>
                <hr>
                <div class="text-center">
                    <h3 class="text-primary">Total: ${{ number_format($sale->total, 2) }}</h3>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
