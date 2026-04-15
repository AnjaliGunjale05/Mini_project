<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\AdminOrderService;
use Illuminate\Http\Request;
use App\Models\Order;

class AdminOrderController extends Controller
{
    protected $orderService;

    public function __construct(AdminOrderService $orderService)
    {
        $this->orderService=$orderService;
    }

    public function index(Request $request){
       
        $orders = $this->orderService->getFilteredOrders($request);
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