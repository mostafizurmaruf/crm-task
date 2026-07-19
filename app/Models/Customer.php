<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[Fillable(['name', 'email', 'phone', 'address', 'last_purchase_at'])]
class Customer extends Model
{
    use HasFactory;

    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class);
    }

    public function assignment(): HasOne
    {
        return $this->hasOne(CustomerAssignment::class);
    }

    protected function casts(): array
    {
        return [
            'last_purchase_at' => 'datetime',
        ];
    }
}
