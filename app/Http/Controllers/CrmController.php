<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Setting;
use App\Models\User;
use App\Services\CrmService;
use Illuminate\Http\Request;

class CrmController extends Controller
{
    public function __construct(protected CrmService $crmService) {

    }

    public function lostCustomers()
    {
        $lostCustomers = $this->crmService->getLostCustomers();
        $employees = User::where('role', 'employee')->get();
        $lostCustomerDays = Setting::getValue('lost_customer_days', 90);

        return view('crm.lost_customers', compact('lostCustomers', 'employees', 'lostCustomerDays'));
    }

    public function assign(Customer $customer)
    {
        $employees = User::where('role', 'employee')->get();

        return view('crm.assign', compact('customer', 'employees'));
    }

    public function doAssign(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'employee_id' => 'required|exists:users,id',
        ]);

        $this->crmService->assignCustomer($request->customer_id, $request->employee_id);

        return redirect()->route('crm.lostCustomers')->with('success', 'Customer assigned successfully.');
    }

    public function sendEmail(Customer $customer)
    {
        $this->crmService->sendReengagementEmail($customer);

        return redirect()->back()->with('success', 'Re-engagement email sent successfully.');
    }

    public function sendSms(Customer $customer)
    {
        $this->crmService->sendReengagementSms($customer);

        return redirect()->back()->with('success', 'Re-engagement SMS sent successfully.');
    }
}
