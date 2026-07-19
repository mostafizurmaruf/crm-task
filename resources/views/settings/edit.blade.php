@extends('layouts.app')

@section('title', 'Settings')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="bi bi-gear"></i> Settings</h4>
</div>

<div class="row g-4">
    <div class="col-lg-12">
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-person-x"></i> CRM Settings</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('settings.update') }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="lost_customer_days" class="form-label">Lost Customer Threshold (Days)</label>
                        <div class="input-group">
                            <input type="number" min="1" class="form-control @error('lost_customer_days') is-invalid @enderror" id="lost_customer_days" name="lost_customer_days" value="{{ old('lost_customer_days', $lostCustomerDays) }}">
                            <span class="input-group-text">days</span>
                            @error('lost_customer_days')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-text">Customers who haven't made a purchase in this many days will be considered "lost" and shown in the CRM dashboard.</div>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg"></i> Save Settings
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
