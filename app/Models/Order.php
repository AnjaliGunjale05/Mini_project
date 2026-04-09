<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'email',
        'phone',
        'country',
        'state',
        'city',
        'landmark',
        'postal_code',
        'total',
        'status',
        'payment_status',
        'transaction_id',
        'razorpay_order_id',
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id', 'id');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
