<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ProductService
{
    public function list(array $filters = []): LengthAwarePaginator
    {
        $query = Product::query();

        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('name', 'like', "%{$filters['search']}%")
                  ->orWhere('sku', 'like', "%{$filters['search']}%");
            });
        }

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return $query->orderBy('created_at', 'desc')->paginate(10);
    }

    public function create(array $data): Product
    {
        return Product::create($data);
    }

    public function find(int $id): Product
    {
        return Product::findOrFail($id);
    }

    public function update(int $id, array $data): Product
    {
        $product = $this->find($id);
        $product->update($data);

        return $product;
    }

    public function delete(int $id): bool
    {
        return $this->find($id)->delete();
    }

    public function getActiveProducts()
    {
        return Product::where('status', true)->where('stock_quantity', '>', 0)->get();
    }
}
