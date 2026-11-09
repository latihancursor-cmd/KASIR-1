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
        'image',
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

    public static function generateSku($categoryId)
    {
        $category = Category::find($categoryId);
        $prefix = $category ? ($category->code ?: strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $category->name), 0, 3))) : 'PRD';
        if (empty($prefix)) {
            $prefix = 'PRD';
        }

        $count = self::where('category_id', $categoryId)->count() + 1;
        $sku = sprintf('%s-%03d', $prefix, $count);

        $counter = $count;
        while (self::where('sku', $sku)->exists()) {
            $counter++;
            $sku = sprintf('%s-%03d', $prefix, $counter);
        }

        return $sku;
    }
}
