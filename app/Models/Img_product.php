<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Img_product extends Model
{
    //
    use HasFactory;
    protected $table = 'img_products'; // Tên bảng trong cơ sở dữ liệu
    protected $fillable = ['id_product', 'img_products', 'sort']; // Các cột được phép thêm/sửa


    // quan hệ với product
    public function product()
    {
        return $this->belongsTo(Product::class, 'id_product');
    }
}
