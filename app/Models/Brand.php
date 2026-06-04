<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Brand extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = ['name', 'logo', 'slug', 'status'];

    /**
     * Accessor: Tự động trả về URL đầy đủ.
     * Giúp Mobile App và Web không cần tự nối chuỗi thủ công.
     */
    protected function getLogoAttribute($value)
    {
        if (!$value) {
            return null;
        }

        // Loại bỏ khoảng trắng thừa nếu có
        $cleanValue = trim($value);

        // Nếu đã là link tuyệt đối (http...) thì trả về luôn
        if (str_starts_with($cleanValue, 'http')) {
            return $cleanValue;
        }

        // Trả về URL đầy đủ dựa trên APP_URL trong file .env
        return url($cleanValue);
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'id_brand', 'id');
    }
}