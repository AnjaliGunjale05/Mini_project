@extends('layouts.app')

@section('content')

<div class="max-w-xl mx-auto mt-10 bg-white p-6 shadow rounded">
    <h2 class="text-xl font-bold mb-4">Complete Payment</h2>

    <button id="rzp-button" class="bg-green-600 text-white px-6 py-2 rounded">
        Pay ₹{{ $order->total }}
    </button>
</div>

<script src="https://checkout.razorpay.com/v1/checkout.js"></script>

<form id="payment-form" action="{{ route('payment.success') }}" method="POST">
    @csrf
    <input type="hidden" name="order_id" value="{{ $order->id }}">
    <input type="hidden" name="razorpay_payment_id" id="payment_id">
</form>

<script>
    var options = {
        "key": "rzp_test_Saev3PP8kBoMSf",
        "amount": "{{ $order->total * 100 }}",
        "currency": "INR",
        "name": "Your Store",
        "description": "Test Payment",

        "handler": function (response) {

            fetch("{{ route('payment.success') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    order_id: "{{ $order->id }}",  // ✅ FIXED
                    razorpay_payment_id: response.razorpay_payment_id
                })
            })
            .then(res => res.json())
            .then(resData => {

                // ✅ REDIRECT TO SUCCESS PAGE
                window.location.href = "/checkout/confirmation/{{ $order->id }}";

            });
        }
    };

    var rzp = new Razorpay(options);

    document.getElementById('rzp-button').onclick = function(e){
        rzp.open();
        e.preventDefault();
    }
</script>

@endsection