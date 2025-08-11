@extends('layouts.app')

@section('content')
<style>
.payment-container {
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 60px 20px;
    background-color: #f5f7fa;
    min-height: 80vh;
}

.payment-box {
    background: white;
    padding: 30px 40px;
    border-radius: 12px;
    box-shadow: 0 6px 18px rgba(0, 0, 0, 0.06);
    max-width: 400px;
    width: 100%;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.payment-box h2 {
    margin-bottom: 20px;
    font-weight: 600;
    color: #0d1a26;
    text-align: center;
}

.payment-box p {
    margin: 12px 0;
    font-size: 15px;
    color: #333;
}

.pay-btn {
    width: 100%;
    padding: 12px 20px;
    background-color: #00b386;
    color: white;
    border: none;
    border-radius: 6px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.3s ease;
}

.pay-btn:hover {
    background-color: #009973;
}

.cancelled-btn {
    background-color: #c43636ff;
}
.cancelled-btn:hover {
    background-color: #a12e2eff;
}

.paid-message {
    padding: 10px;
    background-color: #e6fff2;
    color: #00b386;
    border: 1px solid #00b386;
    border-radius: 6px;
    font-weight: 500;
    text-align: center;
}

</style>

<div class="payment-container">
    <div class="payment-box">
        <h2>Secure Payment</h2>
        <hr>
        <p><strong>Order ID:</strong> {{ $order->id }}</p>
        <p><strong>Total:</strong> ₹{{ number_format($order->total_amount, 2) }}</p>
        <p><strong>Status:</strong> {{ ucfirst($order->payment_status) }}</p>

        <div>
            @if($order->status === 'pending')

            <h3>Pay before time runs out</h3>
            <p id="payment-timer"></p>
        </div>

        <script>
            // Set 20 minutes from now (1200 seconds)
            let remainingTime = 20 * 60;

            function updateTimer() {
                let minutes = Math.floor(remainingTime / 60);
                let seconds = remainingTime % 60;

                document.getElementById("payment-timer").textContent =
                    `Time left: ${minutes}m ${seconds < 10 ? '0' : ''}${seconds}s`;

                if (remainingTime <= 0) {
                    document.getElementById("payment-timer").textContent = "Order expired!";
                    // Optional: redirect or disable pay button
                    document.querySelector("form").style.display = "none";
                    clearInterval(timerInterval);
                    window.location.href = "{{ route('home') }}";
                }

                remainingTime--;
            }

            let timerInterval = setInterval(updateTimer, 1000);
            updateTimer(); // Initial call
        </script>

            <form action="{{ route('payment.mock', $order->id) }}" method="POST">
                @csrf
                <button type="submit" class="pay-btn">Pay Now (Mock)</button>
            </form>
            @elseif($order->status === 'cancelled')
            <button type="submit" class="pay-btn cancelled-btn" onclick="window.location.href = '{{ route('home') }}'">Order Cancelled</button>
            @else
            <p class="paid-message">✅ Payment already completed.</p>
        @endif
    </div>
</div>
@endsection
