<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FavoriteProduct extends Model
{
    //
    use HasFactory;
    protected $table = "favorite_products";
    protected $fillable = [
        'id_product',
        'id_user',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class,  'id_product');
    }
}
