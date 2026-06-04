<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    //
    use HasFactory;
    protected $table = "product_variants";
    protected $fillable = [
        'id_product',
        'option',
        'price',
        'image',
        'stock'
        // 'discount_value'

    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'id_product')->where('status', 1);
    }

    public function orderDetail()
    {
        return $this->hasMany(OrderDetail::class, 'id_variant', 'id');
    }
}
