@extends('layouts.app')

@section('content')
<style>
    /* General Styling */
body {
    font-family: Arial, sans-serif;
    background-color: #f4f7fa;
    margin: 0;
    padding: 0;
    color: #333;
}

.container {
    width: 80%;
    max-width: 900px;
    margin: 50px auto;
    padding: 20px;
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

/* Heading Style */
h1 {
    font-size: 24px;
    color: #333;
    text-align: center;
    margin-bottom: 30px;
}

/* Order Payment Summary */
p {
    font-size: 16px;
    color: #555;
    margin-bottom: 20px;
    text-align: center;
}

strong {
    font-size: 20px;
    color: #000;
}

/* Button Styling */
button#rzp-button {
    display: block;
    margin: 30px auto;
    padding: 12px 30px;
    background-color: #3399cc;
    color: #fff;
    border: none;
    border-radius: 5px;
    font-size: 16px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

button#rzp-button:hover {
    background-color: #267bbd;
}

/* Error/No Order Message */
p.no-order {
    text-align: center;
    font-size: 18px;
    color: #d9534f;
}

/* Responsive Design */
@media (max-width: 768px) {
    .container {
        width: 90%;
    }

    h1 {
        font-size: 20px;
    }

    p {
        font-size: 14px;
    }

    button#rzp-button {
        font-size: 14px;
        padding: 10px 25px;
    }
}

</style>
@php
    // Primary order: prefer $order param, otherwise first of $orders
    $primaryOrder = $order ?? ($orders->first() ?? null);
    // Amount is passed from controller
    $displayAmount = $amount ?? ($primaryOrder->total_amount ?? 0);
@endphp

@if(!$primaryOrder)
    <p>No order found. <a href="{{ route('cart.index') }}">Back to cart</a></p>
@else
    <div class="container">
        <h1>Pay for Order #{{ $primaryOrder->id }}</h1>

        <p>
            @if(count($orders) > 1)
                Paying for {{ count($orders) }} orders. Total:
            @else
                Total:
            @endif
            <strong>₹{{ number_format($displayAmount, 2) }}</strong>
        </p>

        <button id="rzp-button">Pay with Razorpay</button>

        <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
        <script>
            const razorpayKey = "{{ $razorpayKey ?? config('services.razorpay.key') }}";
            const amountPaise = Math.round({{ $displayAmount }} * 100); // paise

            const options = {
                "key": razorpayKey,
                "amount": amountPaise,
                "currency": "INR",
                "name": "{{ config('app.name', 'My Store') }}",
                "description": "Order(s): {{ implode(',', $orders->pluck('id')->toArray()) }}",
                "handler": function (response) {
                    // POST to unified success route that will mark all session orders paid
                    fetch("{{ route('payment.success') }}", {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': "{{ csrf_token() }}",
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            razorpay_payment_id: response.razorpay_payment_id,
                            razorpay_order_id: response.razorpay_order_id ?? null,
                            // optional: include the order IDs explicitly
                            order_ids: {!! json_encode($orders->pluck('id')->toArray()) !!}
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            window.location.href = "{{ route('thank-you') }}";
                        } else {
                            alert('Payment processed but internal update failed.');
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        alert('Payment processed but something went wrong.');
                    });
                },
                "prefill": {
                    "name": "{{ auth()->user()->name }}",
                    "email": "{{ auth()->user()->email }}"
                },
                "theme": {
                    "color": "#3399cc"
                }
            };

            var rzp = new Razorpay(options);
            document.getElementById('rzp-button').onclick = function(e){
                rzp.open();
                e.preventDefault();
            };
        </script>
    </div>
@endif
@endsection
