<?php

namespace App\Services;

use App\Models\Product;
use App\Models\RecentProduct;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class RecentProductService
{
    protected $limit = 8;

    public function store($productId)
    {
        try {
            if (Auth::check()) {
                RecentProduct::updateOrCreate(
                    [
                        'user_id' => Auth::id(),
                        'product_id' => $productId
                    ],
                    [
                        'viewed_at' => now()
                    ]
                );
                $this->limitUserRecords();
            } else {
                $this->storeInSession($productId);
            }
        } catch (Exception $e) {
            Log::error('RecentProduct Store Error: ' . $e->getMessage());
        }
    }

    private function storeInSession($productId)
    {
        try {
            $recent = session()->get('recent_products', []);

            $recent = array_diff($recent, [$productId]);
            array_unshift($recent, $productId);
            $recent = array_slice($recent, 0, $this->limit);

            session()->put('recent_products', $recent);
        } catch (Exception $e) {
            Log::error('RecentProduct Session Store Error: ' . $e->getMessage());
        }
    }

    private function limitUserRecords()
    {
       try{
         $records = RecentProduct::where('user_id', Auth::id())
            ->orderby('viewed_at', 'desc')
            ->get();

        if ($records->count() > $this->limit) {
            $records->slice($this->limit)->each(function ($item) {
                $item->delete();
            });
        }
        } catch(Exception $e){
            Log::error('RecentProduct Limit User Records Error: ' . $e->getMessage());
        }

    }

    public function getRecentProducts()
    {
        try {
        if (Auth::check()) {
            return RecentProduct::with('product')
                ->where('user_id', Auth::id())
                ->orderBy('viewed_at', 'desc')
                ->take($this->limit)
                ->get()
                ->pluck('product');
        }

        $ids = session()->get('recent_products', []);

        return Product::wherein('id', $ids)
            ->get()
            ->sortby(function ($product) use ($ids) {
                return array_search($product->id, $ids);
            });
    } catch(Exception $e){
        Log::error('RecentProduct Get Recent Products Error: ' . $e->getMessage());
        // Return Empty collection on error to avoid breaking the UI 
        return collect(); 
    }
    
    }
}
