@extends('layouts.app')

@section('title', 'Sales')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="bi bi-receipt"></i> Sales</h4>
    <a href="{{ route('sales.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Create Sale
    </a>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('sales.index') }}">
            <div class="input-group">
                <input type="text" class="form-control" name="search" placeholder="Search by invoice #..." value="{{ request('search') }}">
                <button class="btn btn-outline-primary" type="submit"><i class="bi bi-search"></i> Search</button>
                @if(request('search'))
                    <a href="{{ route('sales.index') }}" class="btn btn-outline-secondary"><i class="bi bi-x-lg"></i> Clear</a>
                @endif
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-striped table-hover mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>Invoice #</th>
                        <th>Customer</th>
                        <th>Employee</th>
                        <th>Total</th>
                        <th>Date</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sales as $sale)
                        <tr>
                            <td><code>{{ $sale->invoice_no }}</code></td>
                            <td>{{ $sale->customer->name ?? 'N/A' }}</td>
                            <td>{{ $sale->employee->name ?? 'N/A' }}</td>
                            <td><strong>${{ number_format($sale->total, 2) }}</strong></td>
                            <td>{{ $sale->created_at->format('M d, Y') }}</td>
                            <td class="text-center">
                                <a href="{{ route('sales.show', $sale) }}" class="btn btn-sm btn-outline-info" title="View">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">No sales found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-3">
    {{ $sales->links() }}
</div>
@endsection
