<?php
namespace App\Services;

use App\Models\Order;

class AdminOrderService
{
    public function getFilteredOrders($request)
    {
        $query = Order::with('user');

        //  Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        //  Date filter
        if ($request->filled('from_date') && $request->filled('to_date')) {
            $query->whereBetween('created_at', [
                $request->from_date . ' 00:00:00',
                $request->to_date . ' 23:59:59'
            ]);
        }

        return $query->latest()->paginate(10)->withQueryString();
    }
}