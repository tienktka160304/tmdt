<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Post_Category extends Model
{
    //
    use HasFactory;
    use SoftDeletes;
    protected $table = 'post_categories';
    protected $fillable = [
        'name',
        'status',
        'sort',
        'slug '
    ];

    // quan hệ với posts
    public function posts()
    {
        return $this->hasMany(Post::class, 'id_category')->where('status', 1);
    }
}
