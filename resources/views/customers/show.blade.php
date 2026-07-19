@extends('layouts.app')

@section('title', 'Customer Details')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="bi bi-person"></i> {{ $customer->name }}</h4>
    <a href="{{ route('customers.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Back to Customers
    </a>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-header bg-white">
        <h5 class="mb-0"><i class="bi bi-person-badge"></i> Customer Information</h5>
    </div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-4">
                <p class="text-muted mb-1">Name</p>
                <h5>{{ $customer->name }}</h5>
            </div>
            <div class="col-md-4">
                <p class="text-muted mb-1">Email</p>
                <h5>{{ $customer->email }}</h5>
            </div>
            <div class="col-md-4">
                <p class="text-muted mb-1">Phone</p>
                <h5>{{ $customer->phone }}</h5>
            </div>
            <div class="col-md-12">
                <p class="text-muted mb-1">Address</p>
                <p>{{ $customer->address ?? 'No address provided' }}</p>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card border-primary shadow-sm h-100">
            <div class="card-body text-center">
                <i class="bi bi-cart-check text-primary fs-2"></i>
                <h3 class="mt-2">{{ $stats['total_purchases'] }}</h3>
                <p class="text-muted mb-0">Total Purchases</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-success shadow-sm h-100">
            <div class="card-body text-center">
                <i class="bi bi-currency-dollar text-success fs-2"></i>
                <h3 class="mt-2">${{ number_format($stats['total_spending'], 2) }}</h3>
                <p class="text-muted mb-0">Total Spending</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-info shadow-sm h-100">
            <div class="card-body text-center">
                <i class="bi bi-arrow-repeat text-info fs-2"></i>
                <h3 class="mt-2">{{ $stats['purchase_frequency'] }}</h3>
                <p class="text-muted mb-0">Purchase Frequency</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-warning shadow-sm h-100">
            <div class="card-body text-center">
                <i class="bi bi-calendar text-warning fs-2"></i>
                <h3 class="mt-2">{{ $stats['last_purchase_date'] ? $stats['last_purchase_date']->format('M d, Y') : 'Never' }}</h3>
                <p class="text-muted mb-0">Last Purchase</p>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-header bg-white">
        <h5 class="mb-0"><i class="bi bi-receipt"></i> Purchase History</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-striped table-hover mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>Invoice #</th>
                        <th>Date</th>
                        <th>Employee</th>
                        <th>Total</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($purchaseHistory as $sale)
                        <tr>
                            <td><code>{{ $sale->invoice_no }}</code></td>
                            <td>{{ $sale->created_at->format('M d, Y') }}</td>
                            <td>{{ $sale->employee->name ?? 'N/A' }}</td>
                            <td>${{ number_format($sale->total, 2) }}</td>
                            <td class="text-center">
                                <a href="{{ route('sales.show', $sale) }}" class="btn btn-sm btn-outline-info" title="View">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">No purchase history found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
