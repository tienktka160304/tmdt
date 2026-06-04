<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MainCategory extends Model
{
    //
    //
    use HasFactory;
    use SoftDeletes;
    protected $table = "main_categories";
    protected $fillable = [
        'name',
    ];

    public function sub_category()
    {

        return $this->hasMany(SubCategory::class, 'id_main_category', "id");
    }
}
