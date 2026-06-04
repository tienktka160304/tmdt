<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Discount extends Model
{
    //
    use HasFactory;
    use SoftDeletes;
    protected $table = "discounts";
    protected $fillable = [
        'name',
        'description',
        'value',
        'time_start',
        'time_end',
        'status',
    ];



    public function products()
    {
        return $this->hasMany(Product::class, 'id_discount', 'id');
    }
}
