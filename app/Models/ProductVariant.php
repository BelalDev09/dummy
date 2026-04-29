<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    protected $fillable = [
        'product_id',
        'size',
        'color',
        'color_hex',
        'sku',
        'price',
        'stock',
        'status'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
