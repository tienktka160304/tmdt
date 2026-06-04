<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    //

    protected $table = 'orders_details';
    protected $fillable = [
        'id_variant',
        'id_order',
        'price',
        'quantity'
    ];
    public function productVariant()
    {
        return $this->belongsTo(ProductVariant::class, 'id_variant', "id");
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'id_order');
    }
}
