<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerSerment extends Model
{
    //
    use HasFactory;
    protected $table = "customer_segments";
    protected $fillable = [
        'name',

    ];

    public function Product_customer_segment()
    {

        return $this->hasMany(ProductCustomerSegment::class, 'id_customer_segment', "id");
    }
    // 
    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_customer_segments', 'id_customer_segment', 'id_product');
    }
}
