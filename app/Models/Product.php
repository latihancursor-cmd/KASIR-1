<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'sku',
        'barcode',
        'description',
        'cost_price',
        'price',
        'stock',
        'min_stock',
        'unit',
        'is_active',
        'category_id',
    ];

    protected $casts = [
        'cost_price' => 'decimal:2',
        'price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
