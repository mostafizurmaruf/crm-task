<?php

namespace App\Services;

use App\Mail\ReengagementMail;
use App\Models\Customer;
use App\Models\CustomerAssignment;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class CrmService
{
    public function getLostCustomers(): Collection
    {
        $lostCustomerDays = (int) Setting::getValue('lost_customer_days', 90);
        $threshold = Carbon::now()->subDays($lostCustomerDays);

        return Customer::where('last_purchase_at', '<', $threshold)->orWhereNull('last_purchase_at')->get();
    }

    public function assignCustomer(int $customerId, int $employeeId): CustomerAssignment
    {
        return CustomerAssignment::updateOrCreate(
            ['customer_id' => $customerId],
            [
                'employee_id' => $employeeId,
                'assigned_at' => Carbon::now(),
            ]
        );
    }

    public function getEmployeeAssignments(int $employeeId): Collection
    {
        return CustomerAssignment::where('employee_id', $employeeId)->with('customer')->get();
    }

    public function sendReengagementEmail(Customer $customer): bool
    {
        Mail::to($customer->email)->send(new ReengagementMail($customer));

        Log::info("Re-engagement email sent to customer: {$customer->name} ({$customer->email})");

        return true;
    }

    public function sendReengagementSms(Customer $customer): bool
    {
        Log::info("Re-engagement SMS action logged for customer: {$customer->name} ({$customer->phone})");

        return true;
    }
}
