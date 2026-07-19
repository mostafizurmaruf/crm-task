@extends('layouts.app')

@section('title', 'CRM - Lost Customers')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="bi bi-person-x"></i> Lost Customers</h4>
    <span class="badge bg-danger fs-6">{{ $lostCustomers->count() }} customers</span>
</div>

<p class="text-muted">Customers who haven't made a purchase in {{ $lostCustomerDays }} days.</p>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-striped table-hover mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Last Purchase</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($lostCustomers as $customer)
                        <tr>
                            <td>{{ $customer->name }}</td>
                            <td>{{ $customer->email }}</td>
                            <td>{{ $customer->phone }}</td>
                            <td>{{ $customer->last_purchase_at ? $customer->last_purchase_at->format('M d, Y') : 'Never' }}</td>
                            <td class="text-center">
                                <form action="{{ route('crm.doAssign', $customer) }}" method="POST" class="d-inline">
                                    @csrf
                                    <div class="input-group input-group-sm" style="width: 220px;">
                                        <select class="form-select form-select-sm" name="employee_id" required>
                                            <option value="">Assign employee...</option>
                                            @foreach($employees as $employee)
                                                <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                                            @endforeach
                                        </select>
                                        <input type="hidden" name="customer_id" value="{{ $customer->id }}">
                                        <button type="submit" class="btn btn-outline-primary btn-sm" title="Assign Employee">
                                            <i class="bi bi-person-plus"></i>
                                        </button>
                                    </div>
                                </form>

                                <form action="{{ route('crm.sendEmail', $customer) }}" method="POST" class="d-inline mt-1">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-success mt-1" title="Send Email" onclick="return confirm('Send a re-engagement email to this customer?')">
                                        <i class="bi bi-envelope"></i> Email
                                    </button>
                                </form>

                                <form action="{{ route('crm.sendSms', $customer) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-info mt-1" title="Send SMS" onclick="return confirm('Send an SMS to this customer?')">
                                        <i class="bi bi-chat-dots"></i> SMS
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                <i class="bi bi-check-circle fs-1 d-block mb-2"></i>
                                No lost customers found. Great job!
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
