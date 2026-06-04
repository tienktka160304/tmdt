<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;
use App\Models\User;

class Comment extends Model
{
    //
    //
    use HasFactory;
    protected $table = 'comments'; // Tên bảng trong cơ sở dữ liệu
    protected $fillable = ['id_user', 'id_product', 'content', 'status', 'create_at']; // Các cột được phép thêm/sửa

    public function products()
    {

        return $this->belongsTo(Product::class, 'id_product');
    }

    public function user()
    {

        return $this->belongsTo(User::class, 'id_user')->select('id', 'last_name', 'first_name', 'avatar');
    }
}
