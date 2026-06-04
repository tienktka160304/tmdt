<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class Order extends Model
{

    use HasFactory;

    protected $table = 'orders';
    protected $fillable = [
        'id_user',
        'id_payment',
        'status',
        'thanh_toan',
        'note',
        'phone',
        'address',
        'total_price',
        'thanh_toan'
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
    public function payment()
    {
        return $this->belongsTo(Payment::class, 'id_payment', 'id');
    }

    public function orders_detail()
    {
        return $this->hasMany(OrderDetail::class, 'id_order', 'id');
    }

    // public function payment()
    // {
    //     return $this->belongsTo(payment::class, 'id_payment', 'id');
    // }
}
