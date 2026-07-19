<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Sale;
use App\Models\Setting;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class SaleService
{
    public function create(array $data): Sale
    {
        return DB::transaction(function () use ($data) {
            foreach ($data['items'] as $item) {
                $product = Product::lockForUpdate()->findOrFail($item['product_id']);

                if ($product->stock_quantity < $item['quantity']) {
                    throw new \Exception(
                        "Insufficient stock for product \"{$product->name}\". " .
                        "Available: {$product->stock_quantity}, Requested: {$item['quantity']}."
                    );
                }
            }

            $total = 0;
            $saleItems = [];

            foreach ($data['items'] as $item) {
                $product = Product::findOrFail($item['product_id']);
                $subtotal = $product->price * $item['quantity'];
                $total += $subtotal;

                $saleItems[] = [
                    'product_id' => $product->id,
                    'price' => $product->price,
                    'quantity' => $item['quantity'],
                    'subtotal' => $subtotal,
                ];

                $product->decrement('stock_quantity', $item['quantity']);
            }

            $sale = Sale::create([
                'invoice_no' => $this->generateInvoiceNo(),
                'customer_id' => $data['customer_id'],
                'employee_id' => $data['employee_id'],
                'total' => $total,
            ]);

            $sale->items()->createMany($saleItems);

            $customer = Customer::findOrFail($data['customer_id']);

            $lostCustomerDays = (int) Setting::getValue('lost_customer_days', 90);
            $threshold = Carbon::now()->subDays($lostCustomerDays);

            $wasLostCustomer = $customer->last_purchase_at === null
                || $customer->last_purchase_at->lt($threshold);

            $customer->update(['last_purchase_at' => Carbon::now()]);

            if ($wasLostCustomer && $data['employee_id']) {
                User::where('id', $data['employee_id'])
                    ->increment('kpi_score');
            }

            return $sale->load(['items.product', 'customer', 'employee']);
        });
    }

    public function list(array $filters = []): LengthAwarePaginator
    {
        $query = Sale::with(['customer', 'employee']);

        if (!empty($filters['search'])) {
            $query->where('invoice_no', 'like', "%{$filters['search']}%");
        }

        if (!empty($filters['customer_id'])) {
            $query->where('customer_id', $filters['customer_id']);
        }

        if (!empty($filters['employee_id'])) {
            $query->where('employee_id', $filters['employee_id']);
        }

        if (!empty($filters['date_from'])) {
            $query->where('created_at', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->where('created_at', '<=', $filters['date_to']);
        }

        return $query->orderBy('created_at', 'desc')->paginate(10);
    }

    public function find(int $id): Sale
    {
        return Sale::with(['items.product', 'customer', 'employee'])->findOrFail($id);
    }

    public function generateInvoiceNo(): string
    {
        $timestamp = now()->format('YmdHis');
        $random = strtoupper(substr(uniqid(), -4));

        return "INV-{$timestamp}{$random}";
    }
}
