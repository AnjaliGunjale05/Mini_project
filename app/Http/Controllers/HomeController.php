<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use App\Services\HomeService;

class HomeController extends Controller
{
    protected $homeService;

    public function __construct(HomeService $homeService)
    {
        $this->homeService=$homeService;
    }
    
public function index(){
        $data=$this->homeService->getHomeData();

        return view('home',$data);
    }
}
