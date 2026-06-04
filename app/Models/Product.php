<?php

namespace App\Models;

use Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Product extends Model
{
    //
    use HasFactory;
    use SoftDeletes;
    protected $dates = ['import_date', 'created_at', 'updated_at', 'deleted_at'];
    protected $table = "products";
    // protected $appends = ['variant_price'];
    protected $fillable = [
        'id_brand',
        'id_category',
        'id_discount',
        'name',
        'slug',
        'description',
        'views',
        'status',
        'hot_product',
        'import_date',
        'created_at',
        'updated_at',
        'deleted_at'

    ];


    // API đang viết phần dưới này
    // 
    // Quan hệ với Brand
    // public function getVariantPriceAttribute()
    // {
    //     return $this->productVariants()->orderBy('id', 'asc')->value('price');
    // }

    // Laravel nhận diện getXXXAttribute() để tạo một thuộc tính động xxx.
    // Ở đây, getVariantPriceAttribute() sẽ giúp tạo một thuộc tính động variant_price.
    public function brand()
    {
        return $this->belongsTo(Brand::class, "id_brand")->select('id', 'name', 'logo', 'slug')->where('status', '>', 0);
    }

    // Quan hệ với Category
    public function sub_category()
    {
        return $this->belongsTo(SubCategory::class, 'id_category', "id")->select('id', "id_main_category", 'name', 'slug')->where('status', '>', 0);
    }

    public function discount()
    {
        return $this->belongsTo(Discount::class, 'id_discount', "id")->select('id', 'name', 'description', 'value', 'status', 'time_start', 'time_end')->where('status', '>', 0);
    }
    // bien the
    public function productVariants()
    {
        return $this->hasMany(ProductVariant::class, 'id_product', 'id')->select('id', 'id_product', 'option', 'price', 'image', 'stock')->withSum(['orderDetail as total_buy' => function ($orderQuery) {
            $orderQuery->whereHas('order', function ($orderQuery) {
                $orderQuery->where('status', 3);
            });
        }], 'quantity');
    }

    //
    // khach hang
    public function productCustomerSegments()
    {
        return $this->hasMany(ProductCustomerSegment::class, 'id_product')->select('id', 'id_product', 'id_customer_segment')->with(['customer_segment' => function ($query) {
            $query->select('id', 'name');
        }]);
    }


    public function customerSegments()
    {
        return $this->belongsToMany(CustomerSerment::class, 'product_customer_segments', 'id_product', 'id_customer_segment');
    }

    // Quan hệ với attributes
    public function attributes()
    {
        return $this->hasMany(Attribute_product::class, 'id_product');
    }


    public function images()
    {

        return $this->hasMany(Img_product::class,   'id_product', 'id')
            ->orderBy('sort', 'asc')
            ->select(
                'id',
                'id_product',
                'img_products',
                'sort'
            );
    }
    public function orderDetails()
    {
        return $this->hasManyThrough(
            OrderDetail::class,
            ProductVariant::class,
            'id_product',  // Khóa ngoại trong bảng ProductVariant
            'id_variant',  // Khóa ngoại trong bảng OrderDetail
            'id',          // Khóa chính trong bảng Product
            'id'           // Khóa chính trong bảng ProductVariant
        );
    }

    public function attribute()
    {
        return $this->hasMany(Attribute_product::class,   'id_product', 'id')->select(
            'id',
            "id_product",
            'key',
            "value",
            'sort'
        );
    }
    public function comment()
    {
        return $this->hasMany(Comment::class,   'id_product', 'id')->select(
            'id_product',
            'id_user',
            'content',
            'status'
        )->with(['user' => function ($query) {
            $query->select('id', 'last_name', 'first_name', 'avatar');
        }]);
    }




    public function activeDiscount()
    {
        return $this->hasOne(Discount::class, 'id', 'id_discount')
            ->where('status', 1) // Chỉ lấy discount có status = 1
            ->where(function ($query) {
                $query->whereNull('time_end') // Nếu không có ngày hết hạn thì vẫn lấy
                    ->orWhere('time_end', '>', now()); // Chỉ lấy discount chưa hết hạn
            });
    }
}
