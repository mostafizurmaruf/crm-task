<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\CrmService;

class EmployeeController extends Controller
{
    public function __construct(protected CrmService $crmService) {
        
    }

    public function index()
    {
        $employees = User::where('role', 'employee')->get();

        return view('employees.index', compact('employees'));
    }

    public function assignments(User $employee)
    {
        $assignments = $this->crmService->getEmployeeAssignments($employee->id);

        return view('employees.assignments', compact('employee', 'assignments'));
    }
}
