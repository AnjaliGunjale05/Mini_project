<?php 
namespace App\Services;
use App\Models\Product;
use App\Models\Wishlist;

class WishlistService
{
    // Add to Wishlist

    public function add($userId, $productId)
    {
        Wishlist::firstOrCreate([
        'user_id' => $userId,
        'product_id' =>$productId,

    ]);
    }
//  get all wishlist items of user
    public function getUserWishlist($userId){
        return Wishlist::where('user_id',$userId)->get();

    }
    // Remove Item From Wishlist
    public function remove($userId,$productId)
    {
        return Wishlist::where('user_id',$userId)->where('product_id',$productId)->delete();
    }

    // Check if Product Already exists in wishlist
    public function exist($userId,$productId){

    return Wishlist::where('user_id',$userId)->where('product_id',$productId)->exists();

    }
}