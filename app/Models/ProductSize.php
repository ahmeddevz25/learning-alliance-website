<?php

namespace App\Models;

use App\Models\Product;
use App\Models\ProductSizeItem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductSize extends Model
{
    use HasFactory;
    protected $fillable = [
        'product_id',
        'size_id',
        'price',
        'stock',
        'is_active',
    ];

    // Define the inverse relationship to Product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function sizeItem()
    {
        return $this->belongsTo(ProductSizeItem::class, 'size_id');
    }
}
