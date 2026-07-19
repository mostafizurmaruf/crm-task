@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="bi bi-speedometer2"></i> Admin Dashboard</h4>
    <span class="text-muted">{{ now()->format('l, F j, Y') }}</span>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card border-primary shadow-sm h-100">
            <div class="card-body d-flex align-items-center">
                <div class="rounded-circle bg-primary bg-opacity-10 p-3 me-3">
                    <i class="bi bi-box-seam text-primary fs-4"></i>
                </div>
                <div>
                    <h6 class="text-muted mb-1">Total Products</h6>
                    <h3 class="mb-0">{{ $stats['total_products'] }}</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-success shadow-sm h-100">
            <div class="card-body d-flex align-items-center">
                <div class="rounded-circle bg-success bg-opacity-10 p-3 me-3">
                    <i class="bi bi-people text-success fs-4"></i>
                </div>
                <div>
                    <h6 class="text-muted mb-1">Total Customers</h6>
                    <h3 class="mb-0">{{ $stats['total_customers'] }}</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-info shadow-sm h-100">
            <div class="card-body d-flex align-items-center">
                <div class="rounded-circle bg-info bg-opacity-10 p-3 me-3">
                    <i class="bi bi-receipt text-info fs-4"></i>
                </div>
                <div>
                    <h6 class="text-muted mb-1">Total Sales</h6>
                    <h3 class="mb-0">{{ $stats['total_sales'] }}</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-warning shadow-sm h-100">
            <div class="card-body d-flex align-items-center">
                <div class="rounded-circle bg-warning bg-opacity-10 p-3 me-3">
                    <i class="bi bi-calendar-check text-warning fs-4"></i>
                </div>
                <div>
                    <h6 class="text-muted mb-1">Today's Sales</h6>
                    <h3 class="mb-0">{{ $stats['today_sales'] }}</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-dark shadow-sm h-100">
            <div class="card-body d-flex align-items-center">
                <div class="rounded-circle bg-dark bg-opacity-10 p-3 me-3">
                    <i class="bi bi-currency-dollar text-dark fs-4"></i>
                </div>
                <div>
                    <h6 class="text-muted mb-1">Total Revenue</h6>
                    <h3 class="mb-0">${{ number_format($stats['total_revenue'], 2) }}</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-danger shadow-sm h-100">
            <div class="card-body d-flex align-items-center">
                <div class="rounded-circle bg-danger bg-opacity-10 p-3 me-3">
                    <i class="bi bi-person-x text-danger fs-4"></i>
                </div>
                <div>
                    <h6 class="text-muted mb-1">Lost Customers</h6>
                    <h3 class="mb-0">{{ $stats['lost_customers_count'] }}</h3>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-header bg-white">
        <h5 class="mb-0"><i class="bi bi-graph-up"></i> Employee KPI Summary</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-striped table-hover mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>Employee Name</th>
                        <th class="text-center">KPI Score</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($stats['employee_kpi_summary'] as $employee)
                        <tr>
                            <td>{{ $employee->name }}</td>
                            <td class="text-center">
                                <span class="badge bg-primary fs-6">{{ $employee->kpi_score }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="text-center text-muted py-4">No employees found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
