@extends('layouts.app')

@section('title', 'Employee Dashboard')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="bi bi-speedometer2"></i> My Dashboard</h4>
    <span class="text-muted">{{ now()->format('l, F j, Y') }}</span>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card border-primary shadow-sm h-100">
            <div class="card-body d-flex align-items-center">
                <div class="rounded-circle bg-primary bg-opacity-10 p-3 me-3">
                    <i class="bi bi-people text-primary fs-4"></i>
                </div>
                <div>
                    <h6 class="text-muted mb-1">Assigned Customers</h6>
                    <h3 class="mb-0">{{ $stats['assigned_customers'] }}</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-success shadow-sm h-100">
            <div class="card-body d-flex align-items-center">
                <div class="rounded-circle bg-success bg-opacity-10 p-3 me-3">
                    <i class="bi bi-receipt text-success fs-4"></i>
                </div>
                <div>
                    <h6 class="text-muted mb-1">My Sales</h6>
                    <h3 class="mb-0">{{ $stats['total_sales'] }}</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-info shadow-sm h-100">
            <div class="card-body d-flex align-items-center">
                <div class="rounded-circle bg-info bg-opacity-10 p-3 me-3">
                    <i class="bi bi-currency-dollar text-info fs-4"></i>
                </div>
                <div>
                    <h6 class="text-muted mb-1">My Revenue</h6>
                    <h3 class="mb-0">${{ number_format($stats['total_revenue'], 2) }}</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-warning shadow-sm h-100">
            <div class="card-body d-flex align-items-center">
                <div class="rounded-circle bg-warning bg-opacity-10 p-3 me-3">
                    <i class="bi bi-graph-up text-warning fs-4"></i>
                </div>
                <div>
                    <h6 class="text-muted mb-1">KPI Score</h6>
                    <h3 class="mb-0">{{ $stats['kpi_score'] }}</h3>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card border-secondary shadow-sm h-100">
            <div class="card-body d-flex align-items-center">
                <div class="rounded-circle bg-secondary bg-opacity-10 p-3 me-3">
                    <i class="bi bi-calendar-check text-secondary fs-4"></i>
                </div>
                <div>
                    <h6 class="text-muted mb-1">Today's Sales</h6>
                    <h3 class="mb-0">{{ $stats['today_sales'] }}</h3>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
