<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'stock',
        'image',
    
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function images(){
        return $this->hasMany(ProductImages::class);
    }

    public function recentProducts()
    {
        return $this->hasMany(RecentProduct::class,'product_id');
    }
    
}
