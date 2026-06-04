<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserReview extends Model
{
    use HasFactory;
    protected $table = "user_reviews";
    protected $fillable = [
        'id_user',
        'content',

    ];
    // quan hệ với user
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
