<?php

namespace App\Http\Controllers;

use App\Http\Requests\Customer\StoreCustomerRequest;
use App\Http\Requests\Customer\UpdateCustomerRequest;
use App\Models\Customer;
use App\Services\CustomerService;

class CustomerController extends Controller
{
    public function __construct(protected CustomerService $customerService) {

    }

    public function index()
    {
        $customers = $this->customerService->list(request()->only('search'));

        return view('customers.index', compact('customers'));
    }

    public function create()
    {
        return view('customers.create');
    }

    public function store(StoreCustomerRequest $request)
    {
        $this->customerService->create($request->validated());

        return redirect()->route('customers.index')->with('success', 'Customer created successfully.');
    }

    public function show(Customer $customer)
    {
        $customer = $this->customerService->find($customer->id);
        $stats = $this->customerService->getCustomerStats($customer);
        $purchaseHistory = $customer->sales;

        return view('customers.show', compact('customer', 'stats', 'purchaseHistory'));
    }

    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    public function update(UpdateCustomerRequest $request, Customer $customer)
    {
        $this->customerService->update($customer->id, $request->validated());

        return redirect()->route('customers.index')->with('success', 'Customer updated successfully.');
    }

    public function destroy(Customer $customer)
    {
        $this->customerService->delete($customer->id);

        return redirect()->route('customers.index')->with('success', 'Customer deleted successfully.');
    }
}
