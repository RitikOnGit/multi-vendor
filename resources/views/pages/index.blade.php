@extends('layouts.app')

@section('title', 'Product Page')

@section('content')
    <!-- Products Grid -->
    {{-- dd($products) --}}
    <div class="products" id="product-list">
    @foreach ($products as $product)
        <div class="product">
            <img src="{{ asset('storage/'.$product->image) }}" alt="{{ $product->name }}" />
            <h3>{{ $product->name }}</h3>
            <p>₹{{ number_format($product->price, 2) }}</p>
            <button
                class="add-to-cart-btn"
                data-id="{{ $product->id }}"
                data-name="{{ $product->name }}"
                data-price="{{ $product->price }}"
            >
                Add to Cart
            </button>
        </div>
    @endforeach
</div>

<div id="toast" class="toast"></div>

    <!-- Cart Sidebar -->
    <div class="cart-sidebar" id="cart-sidebar">
        <button class="close-btn" onclick="toggleCart()">×</button>
        <h2>Your Cart</h2>
        <div id="cart-items">
            <p>No items in cart.</p>
        </div>
        <div class="cart-button">
            <button onclick="window.location.href='{{ route('cart.index') }}'">View cart</button>
        </div>
    </div>
@endsection

@section('scripts')

    <script>

        function showToast(message, isError = false) {
            const toast = document.getElementById('toast');
            toast.textContent = message;
            toast.className = 'toast show' + (isError ? ' error' : '');
            setTimeout(() => {
                toast.classList.remove('show');
            }, 2500);
        }

        function renderCartItems(cartData) {
            const cartItems = document.getElementById("cart-items");
            const cartCount = document.getElementById("cart-count");

            if (!cartData || cartData.length === 0) {
                cartCount.textContent = 0;
                cartItems.innerHTML = "<p>No items in cart.</p>";
                return;
            }

            // Update count badge
            cartCount.textContent = cartData.reduce((sum, item) => sum + item.qty, 0);

            // Render list
            cartItems.innerHTML = "";
            cartData.forEach(item => {
                const div = document.createElement("div");
                div.className = "cart-item";
                div.innerHTML = `
                <p><strong>${item.name}</strong></p>
                <p>Qty: ${item.qty}</p>
                <p>Price: ₹${(item.price * item.qty).toFixed(2)}</p>
            `;
                cartItems.appendChild(div);
            });
        }

        function fetchCartData() {
            fetch("{{ route('cart.data') }}")
                .then(response => response.json())
                .then(data => {
                    renderCartItems(data.cart || []);
                })
                .catch(err => console.error("Error fetching cart:", err));
        }

        document.addEventListener('DOMContentLoaded', function () {
            // Load cart on page load
            fetchCartData();

            const buttons = document.querySelectorAll('.add-to-cart-btn');

            buttons.forEach(button => {
                button.addEventListener('click', function () {
                    const productId = this.dataset.id;

                    fetch("{{ route('cart.store') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        },
                        body: JSON.stringify({
                            product_id: productId,
                            quantity: 1
                        })
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                showToast("Product added to cart!");
                                fetchCartData(); // Refresh sidebar
                            } else {
                                showToast(data.error || 'Something went wrong', true);
                            }
                        })
                        .catch(err => {
                            console.error(err);
                            showToast('Error adding to cart', true);
                        });
                });
            });
        });
    </script>

@endsection
