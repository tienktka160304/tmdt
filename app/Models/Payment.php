<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;


    protected $table = 'payments';

    protected $fillable = [
        'payment_method',
        'status',
        'bank',
        'bank_number',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class,  'id');
    }
}
