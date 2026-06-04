<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Post extends Model
{
  // trait TraitName
  // {
  // }

  use HasFactory;
  use SoftDeletes;
  protected $dates = ['deleted_at'];
  protected $table = 'posts';
  protected $fillable = [
    'id_user',
    'id_category',
    'title',
    'short_description',
    'status',
    'image',
    'content',
    'published_date',
    'slug',
    'hot',
  ];
  // quan hệ user
  public function user()
  {
    return $this->belongsTo(User::class, 'id_user')->select('id', 'last_name', "first_name", 'avatar');
  }
  // Quan hệ với Category
  public function post_category()
  {
    return $this->belongsTo(Post_Category::class, 'id_category')->select('id', 'name', "sort", 'status')->where('status', 1);
  }
}
