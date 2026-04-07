<?php
namespace App\Services;
use App\Models\Category;
use App\Models\Product;
use Exception;

class HomeService{
    public function getHomeData(){
        try{
            return ['products'=> Product::latest()->take(8)->get(),
            'categories' => Category::all(),
            ];
        }
        catch(Exception $e){
            return $e;
        }
    }
}