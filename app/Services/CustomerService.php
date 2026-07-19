<?php

namespace App\Services;

use App\Models\Customer;
use Carbon\Carbon;

class CustomerService
{
    public function list(array $filters = [])
    {
        $query = Customer::query();

        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('name', 'like', "%{$filters['search']}%")
                  ->orWhere('email', 'like', "%{$filters['search']}%")
                  ->orWhere('phone', 'like', "%{$filters['search']}%");
            });
        }

        return $query->orderBy('created_at', 'desc')->paginate(10);
    }

    public function create(array $data): Customer
    {
        return Customer::create($data);
    }

    public function find(int $id): Customer
    {
        $customer = Customer::withCount('sales')->findOrFail($id);

        $customer->load([
            'sales' => fn ($q) => $q->latest(),
        ]);

        return $customer;
    }

    public function update(int $id, array $data): Customer
    {
        $customer = $this->find($id);
        $customer->update($data);

        return $customer;
    }

    public function delete(int $id): bool
    {
        return $this->find($id)->delete();
    }

    public function getCustomerStats(Customer $customer): array
    {
        $stats = $customer->sales()
            ->selectRaw('COUNT(*) as total_purchases, SUM(total) as total_spending')
            ->first();

        $totalPurchases = (int) ($stats->total_purchases ?? 0);
        $totalSpending = (float) ($stats->total_spending ?? 0);

        $purchaseFrequency = 0;
        if ($totalPurchases > 1) {
            $firstPurchase = $customer->sales()->oldest('created_at')->first();
            $lastPurchase = $customer->sales()->latest('created_at')->first();

            if ($firstPurchase && $lastPurchase) {
                $daysBetween = $firstPurchase->created_at->diffInDays($lastPurchase->created_at);
                $purchaseFrequency = $daysBetween > 0
                    ? round($totalPurchases / $daysBetween, 2)
                    : $totalPurchases;
            }
        }

        $lastPurchaseDate = $customer->sales()
            ->latest('created_at')
            ->value('created_at');

        return [
            'total_purchases' => $totalPurchases,
            'total_spending' => $totalSpending,
            'purchase_frequency' => $purchaseFrequency,
            'last_purchase_date' => $lastPurchaseDate,
        ];
    }

    public function getLostCustomers(int $days)
    {
        $threshold = Carbon::now()->subDays($days);

        return Customer::where('last_purchase_at', '<', $threshold)
            ->orWhereNull('last_purchase_at')
            ->get();
    }
}
