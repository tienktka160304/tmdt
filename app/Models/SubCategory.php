<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubCategory extends Model
{
    //
    use HasFactory;
    use SoftDeletes;
    protected $table = "sub_categories";
    protected $fillable = [
        'id_main_category',
        'name',
        'slug',
        'image',
        'status',
        'sort',
        'created_at',
        'updated_at',
    ];

    public function products()
    {

        return $this->hasMany(Product::class, 'id_category', "id");
    }

    public function mainCategory()
    {

        return $this->belongsTo(MainCategory::class, 'id_main_category', "id");
    }
}
