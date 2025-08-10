@extends('layouts.app')

@section('content')
<style>
.title1 {
    color: #333;
    margin-bottom: 20px;
}
.container {
    margin: 0 auto;
    padding: 20px;
}

/* Vendor Block Styling */
.vendor-block {
    background-color: #fff;
    border: 1px solid #ddd;
    margin-bottom: 30px;
    padding: 20px;
    border-radius: 5px;
}

/* Vendor Name */
.vendor-block h3 {
    margin-top: 0;
    margin-bottom: 15px;
}

/* Table Styling */
.vendor-block table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 15px;
}

.vendor-block th, .vendor-block td {
    border: 1px solid #ddd;
    padding: 10px;
    text-align: left;
}

.vendor-block th {
    background-color: #f0f0f0;
}

/* Quantity Adjustment Styling */
.quantity-controls {
    display: flex;
    align-items: center;
}
button {
    background-color: #3490dc;
    color: #fff;
    border: none;
    padding: 6px 12px;
    cursor: pointer;
    border-radius: 3px;
}

button.quantity-btn {
    background-color: #3490dc;
    color: white;
    border: none;
    padding: 6px 10px;
    cursor: pointer;
    font-size: 18px;
    border-radius: 3px;
    margin: 0 5px;
}

button.quantity-btn:hover {
    background-color: #2779bd;
}

input[name="quantity"] {
    width: 60px;
    padding: 5px;
    text-align: center;
}

/* Vendor Total */
.vendor-block p {
    font-weight: bold;
    font-size: 16px;
    color: #222;
}

/* Checkout Button */
form[action="{{ route('checkout') }}"] button {
    background-color: #38c172;
    padding: 10px 20px;
    font-size: 16px;
    margin-top: 20px;
}

form[action="{{ route('checkout') }}"] button:hover {
    background-color: #2d995b;
}
</style>

<div class="container">

<h1 class="title1">Your Cart</h1>

@if($groups->isEmpty())
    <p>Your cart is empty.</p>
@else
    @foreach($groups as $group)
        <div class="vendor-block">
            <h3>{{ $group->vendor->shop_name ?? $group->vendor->user->name }}</h3>
            <table>
                <thead><tr><th>Product</th><th>Qty</th><th>Price</th><th>Subtotal</th><th></th></tr></thead>
                <tbody>
                @foreach($group->items as $item)
                    <tr>
                        <td>{{ $item->product->name }}</td>
                        <td>
                            <form action="{{ route('cart.update', $item->id ?? $item->product->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <div class="quantity-controls">
                                    <button type="button" class="quantity-btn decrease" data-item-id="{{ $item->id ?? $item->product->id }}">-</button>
                                    <input type="number" name="quantity" value="{{ $item->quantity }}" readonly />
                                    <button type="button" class="quantity-btn increase" data-item-id="{{ $item->id ?? $item->product->id }}">+</button>
                                </div>
                                <button type="submit" style="display: none;">Update</button> <!-- Hidden update button for form submission -->
                            </form>
                        </td>
                        <td>{{ $item->product->price }}</td>
                        <td>{{ $item->subtotal }}</td>
                        <td>
                            <form action="{{ route('cart.destroy', $item->id ?? $item->product->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button>Remove</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <p>Vendor total: {{ $group->total }}</p>
            <form action="{{ route('checkout.vendor') }}" method="POST">
            @csrf
            <input type="hidden" name="vendor_id" value="{{ $group->vendor->id }}">
            <button type="submit">Checkout This Vendor</button>
        </form>
        </div>
    @endforeach

    <form action="{{ route('checkout') }}" method="POST">
        @csrf
        <button type="submit">Checkout</button>
    </form>
@endif
</div>

<script>
    document.querySelectorAll('.quantity-btn').forEach(button => {
        button.addEventListener('click', function () {
            const input = this.closest('form').querySelector('input[name="quantity"]');
            let currentQty = parseInt(input.value) || 1;

            if (this.classList.contains('increase')) {
                input.value = currentQty + 1;
            } else if (this.classList.contains('decrease') && currentQty > 1) {
                input.value = currentQty - 1;
            }

            this.closest('form').querySelector('button[type="submit"]').click();
        });
    });

    function updateCartCount() {
        let count = 0;

        document.querySelectorAll('input[name="quantity"]').forEach(input => {
            count += parseInt(input.value) || 0;
        });

        const cartCountElem = document.getElementById('cart-count');
        if (cartCountElem) {
            cartCountElem.textContent = count;
        }
    }

    // Run on page load
    document.addEventListener('DOMContentLoaded', updateCartCount);
</script>

@endsection
