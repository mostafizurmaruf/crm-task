@extends('layouts.app')

@section('title', 'Employee Assignments')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="bi bi-people"></i> Assignments - {{ $employee->name }}</h4>
    <a href="{{ route('employees.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Back to Employees
    </a>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-4">
                <p class="text-muted mb-1">Name</p>
                <h5><i class="bi bi-person-badge"></i> {{ $employee->name }}</h5>
            </div>
            <div class="col-md-4">
                <p class="text-muted mb-1">Email</p>
                <h5>{{ $employee->email }}</h5>
            </div>
            <div class="col-md-4">
                <p class="text-muted mb-1">KPI Score</p>
                <h5>
                    <span class="badge bg-primary fs-5">{{ $employee->kpi_score }}</span>
                </h5>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-header bg-white">
        <h5 class="mb-0"><i class="bi bi-people"></i> Assigned Customers ({{ $assignments->count() }})</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-striped table-hover mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Last Purchase</th>
                        <th>Assigned At</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($assignments as $assignment)
                        <tr>
                            <td>{{ $assignment->customer->name ?? 'N/A' }}</td>
                            <td>{{ $assignment->customer->email ?? 'N/A' }}</td>
                            <td>{{ $assignment->customer->phone ?? 'N/A' }}</td>
                            <td>{{ optional($assignment->customer)->last_purchase_at ? $assignment->customer->last_purchase_at->format('M d, Y') : 'Never' }}</td>
                            <td>{{ $assignment->assigned_at->format('M d, Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">No customers assigned to this employee</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
