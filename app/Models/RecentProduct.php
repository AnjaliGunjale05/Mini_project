<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecentProduct extends Model
{
    protected $fillable = [
        'user_id',
        'product_id',
        'viewed_at',
    ];

    public function product(){
        return $this->belongsTo(product::class);
    }
}
