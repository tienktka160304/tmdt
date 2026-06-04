<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RelateProduct extends Model
{
    //
    //
    use HasFactory;
    protected $table = "relate_products";
    protected $fillable = [
        'id_product_main',
        'id_product_sub'

    ];

    // public function product()
    // {

    //     return $this->hasMany(Product::class, 'id_product_main', "id");
    // }
}
