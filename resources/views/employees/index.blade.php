@extends('layouts.app')

@section('title', 'Employees')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="bi bi-person-badge"></i> Employees</h4>
</div>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-striped table-hover mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th class="text-center">KPI Score</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($employees as $employee)
                        <tr>
                            <td>
                                <i class="bi bi-person-badge"></i> {{ $employee->name }}
                            </td>
                            <td>{{ $employee->email }}</td>
                            <td class="text-center">
                                @php $score = $employee->kpi_score ?? 0; @endphp
                                <span class="badge {{ $score >= 80 ? 'bg-success' : ($score >= 50 ? 'bg-warning' : 'bg-danger') }} fs-6">
                                    {{ $score }}
                                </span>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('employees.assignments', $employee) }}" class="btn btn-sm btn-outline-info" title="View Assignments">
                                    <i class="bi bi-people"></i> View Assignments
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">No employees found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection