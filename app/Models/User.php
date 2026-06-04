<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Notifications\VerifyEmailNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = "users";

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */

    protected $fillable = [
        'last_name',
        'first_name',
        'email',
        'password',
        'roles',
        'dob',
        'password',
        'account_lock',
        'address',
        'gender',
        'phone',
        'avatar'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    // truy xuat se an di truong nay
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'roles' => 'integer',
        ];
    }
    // quan hệ với posts
    public function posts()
    {
        return $this->hasMany(Post::class, 'id_user');
    }
    // quan hệ với orders
    public function orders()
    {
        return $this->hasMany(Order::class, 'id_user');
    }
    public function orders_detail()
    {
        return $this->hasMany(Order::class, 'id_user');
    }
    // quan hệ với commments
    public function comments()
    {
        return $this->hasMany(Comment::class, 'id_user');
    }
    // quan hệ với user_review
    public function user_review()
    {
        return $this->hasMany(UserReview::class, 'id_user');
    }


    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmailNotification());
    }
}
