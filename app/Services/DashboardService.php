<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Sale;
use App\Models\Setting;
use App\Models\User;
use Carbon\Carbon;

class DashboardService
{
    public function getAdminStats(): array
    {
        $lostCustomerDays = (int) Setting::getValue('lost_customer_days', 90);
        $threshold = Carbon::now()->subDays($lostCustomerDays);

        $lostCustomersCount = Customer::where('last_purchase_at', '<', $threshold)
            ->orWhereNull('last_purchase_at')
            ->count();

        $employeeKpiSummary = User::where('role', 'employee')
            ->select('id', 'name', 'kpi_score')
            ->orderBy('kpi_score', 'desc')
            ->get();

        return [
            'total_products' => Product::count(),
            'total_customers' => Customer::count(),
            'total_sales' => Sale::count(),
            'today_sales' => Sale::whereDate('created_at', Carbon::today())->count(),
            'total_revenue' => (float) Sale::sum('total'),
            'lost_customers_count' => $lostCustomersCount,
            'employee_kpi_summary' => $employeeKpiSummary,
        ];
    }

    public function getEmployeeStats(int $employeeId): array
    {
        $employee = User::findOrFail($employeeId);

        $salesQuery = Sale::where('employee_id', $employeeId);

        $totalSales = (clone $salesQuery)->count();
        $totalRevenue = (float) (clone $salesQuery)->sum('total');
        $todaySales = (clone $salesQuery)->whereDate('created_at', Carbon::today())->count();

        $assignedCustomers = Customer::whereHas('assignment', function ($q) use ($employeeId) {
            $q->where('employee_id', $employeeId);
        })->count();

        return [
            'employee_id' => $employee->id,
            'employee_name' => $employee->name,
            'kpi_score' => $employee->kpi_score,
            'total_sales' => $totalSales,
            'total_revenue' => $totalRevenue,
            'today_sales' => $todaySales,
            'assigned_customers' => $assignedCustomers,
        ];
    }
}
