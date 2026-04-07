<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Wishlist;
use App\Services\WishlistService;

class WishlistController extends Controller
{
    protected $wishlistService;
    public function __construct(WishlistService $wishlistService){
        $this->wishlistService=$wishlistService;
    }


    public function add($id)
    {

    if( !auth()->check()){
        return redirect()->route('login')->with('error','Please login first');
    }

    $this->wishlistService->add(auth()->id(),$id);

    return back()->with('success','Added to Wishlist');
    }
    
    // Remove from wishlist
    public function remove($id)
    {
        $this->wishlistService->remove(auth()->id(), $id);

        return back()->with('success', 'Removed from wishlist');
    }

    // Show wishlist page
    public function index()
    {
        $wishlist = $this->wishlistService->getUserWishlist(auth()->id());

        return view('wishlist.index', compact('wishlist'));
    }
}
