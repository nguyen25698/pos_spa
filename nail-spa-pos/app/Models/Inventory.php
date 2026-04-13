<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category',
        'description',
        'quantity',
        'min_quantity',
        'cost_price',
        'sale_price',
        'unit',
        'is_active',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'min_quantity' => 'integer',
        'cost_price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function transactionItems()
    {
        return $this->hasMany(TransactionItem::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeLowStock($query)
    {
        return $query->whereColumn('quantity', '<=', 'min_quantity');
    }

    public function isLowStock()
    {
        return $this->quantity <= $this->min_quantity;
    }

    public function decrementQuantity($amount = 1)
    {
        $this->decrement('quantity', $amount);
    }

    public function incrementQuantity($amount = 1)
    {
        $this->increment('quantity', $amount);
    }
}
