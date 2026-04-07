<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

class AdminOrderController extends Controller
{
    public function index(){
        $orders = Order::with('user')->latest()->paginate(10);
        return view('admin.orders.index', compact('orders'));
    }

    public function show($orderId){
        $order = Order::with('items.product', 'user')->findOrFail($orderId);
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, $orderId){
        $request->validate(['status'=>'required|in:pending,shipped,delivered']);
        $order = Order::findOrFail($orderId);
        $order->status = $request->status;
        $order->save();
        return back()->with('success','Status updated!');
    }
}