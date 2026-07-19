@extends('layouts.app')

@section('title', 'CRM - Assign Customer')

@section('content')
<div class="mb-4">
    <a href="{{ route('crm.lostCustomers') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left"></i> Back to Lost Customers
    </a>
</div>

<h4 class="mb-4"><i class="bi bi-person-plus"></i> Assign Customer to Employee</h4>

<div class="card">
    <div class="card-body">
        <div class="row mb-3">
            <div class="col-md-6">
                <strong>Customer:</strong> {{ $customer->name }}<br>
                <strong>Email:</strong> {{ $customer->email }}<br>
                <strong>Phone:</strong> {{ $customer->phone }}
            </div>
        </div>

        <form action="{{ route('crm.doAssign', $customer) }}" method="POST">
            @csrf
            <input type="hidden" name="customer_id" value="{{ $customer->id }}">
            <div class="mb-3">
                <label for="employee_id" class="form-label">Select Employee</label>
                <select class="form-select @error('employee_id') is-invalid @enderror" id="employee_id" name="employee_id" required>
                    <option value="">Choose an employee...</option>
                    @foreach($employees as $employee)
                        <option value="{{ $employee->id }}" {{ optional($customer->assignment)->employee_id == $employee->id ? 'selected' : '' }}>
                            {{ $employee->name }} (KPI: {{ $employee->kpi_score }})
                        </option>
                    @endforeach
                </select>
                @error('employee_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-check-lg"></i> Assign Customer
            </button>
        </form>
    </div>
</div>
@endsection
