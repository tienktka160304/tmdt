<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    //
    use HasFactory;
    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $table = 'banners'; // Tên bảng trong cơ sở dữ liệu
    protected $fillable = ['position', 'image', 'link', 'title1', 'title2', 'sort']; // Các cột được phép
}
