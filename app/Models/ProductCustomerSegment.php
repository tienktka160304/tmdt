<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCustomerSegment extends Model
{
    //
    //
    use HasFactory;
    protected $table = "product_customer_segments";
    protected $fillable = [
        'id_product',
        'id_customer_segment'
    ];

    public function customer_segment()
    {

        return $this->belongsTo(CustomerSerment::class, 'id_customer_segment');
    }

    public function product()
    {
        //bang trung gian
        return $this->belongsTo(Product::class, 'id_product');
    }
}
