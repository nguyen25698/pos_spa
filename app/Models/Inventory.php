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
        'sku',
        'quantity',
        'unit_price',
        'reorder_level',
        'supplier',
        'description',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'unit_price' => 'decimal:2',
        'reorder_level' => 'integer',
    ];

    public function isLowStock(): bool
    {
        return $this->quantity <= $this->reorder_level;
    }
}
