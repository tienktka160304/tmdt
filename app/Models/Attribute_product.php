<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attribute_product extends Model
{
    //
    use HasFactory;
    protected $table = 'attribute_products'; // Tên bảng 
    protected $fillable = ['id_product', 'key', 'value', 'sort']; // Các cột được phép thêm/sửa


    // Thuộc tính thuộc về một sản phẩm
    public function product()
    {
        return $this->belongsTo(Product::class, 'id_product');
    }
}
