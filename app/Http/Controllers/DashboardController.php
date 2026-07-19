<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(protected DashboardService $dashboardService) {
        
    }

    public function index()
    {
        $user = auth()->user();

        if ($user->isAdmin()) {
            $stats = $this->dashboardService->getAdminStats();

            return view('dashboard.admin', compact('stats'));
        }

        $stats = $this->dashboardService->getEmployeeStats($user->id);

        return view('dashboard.employee', compact('stats'));
    }
}
