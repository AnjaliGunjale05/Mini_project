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
        key: "rzp_test_SbMIU9yifGnU5b",
        amount: "{{ $order->total * 100 }}",
        currency: "INR",
        name: "Your Store",
        description: "Test Payment",
        order_id: "{{ $razorpayOrderId }}", // ✅ comma fixed

        handler: function (response) {

            fetch("{{ route('payment.success') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    order_id: "{{ $order->id }}",
                    razorpay_payment_id: response.razorpay_payment_id,
                    razorpay_order_id: response.razorpay_order_id,
                    razorpay_signature: response.razorpay_signature
                })
            })
            .then(res => res.json())
            .then(data => {

                if (data.success) {
                    // ✅ Redirect to confirmation page
                    window.location.href = data.redirect_url;
                } else {
                    alert("Payment verification failed");
                }

            })
            .catch(err => {
                console.error(err);
                alert("Something went wrong during payment");
            });
        }
    };

    var rzp = new Razorpay(options);

    document.getElementById('rzp-button').onclick = function(e){
        rzp.open();
        e.preventDefault();
    };
</script>

@endsection