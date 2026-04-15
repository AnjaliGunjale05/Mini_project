
<h2>Order Confirmed 🎉</h2>
<p> Hi {{$order->name}},</p>
<p>Your order has been placed successfully! </p>
<p><strong>Order ID: </strong>{{$order->id}}</p>
<p><strong>Total: </strong>{{$order->total}}</p>

<h4>Items:</h4>
<ul>
    @foreach($order->items as $item)
    <li>
        {{$item->product->name}} - Qty: {{$item->quantity}}
    </li>
    @endforeach
</ul>
<br>

<!--  VIEW ORDER BUTTON -->
<a href="{{ url('/my-orders/' . $order->id) }}"
    style="background:#2563eb;color:white;padding:10px 15px;border-radius:5px;text-decoration:none;">
    View Your Order
</a>
<p>Thank you for shoping with us!</p>
