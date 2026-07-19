<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'sku', 'price', 'stock_quantity', 'description', 'status'])]
class Product extends Model
{
    use HasFactory;

    public function saleItems(): HasMany
    {
        return $this->hasMany(SaleItem::class);
    }

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
        ];
    }
}
