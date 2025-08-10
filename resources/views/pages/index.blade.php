@extends('layouts.app')

@section('title', 'Product Page')

@section('content')
    <!-- Products Grid -->
    {{-- dd($products) --}}
    <div class="products" id="product-list">
    @foreach ($products as $product)
        <div class="product">
            <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" />
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
    </div>
@endsection

@section('scripts')
     <!-- <script>
        let cart = [];

        function updateCart() {
            const cartItems = document.getElementById("cart-items");
            const cartCount = document.getElementById("cart-count");

            // Update cart count badge
            cartCount.textContent = cart.reduce((sum, item) => sum + item.qty, 0);

            // If cart is empty
            if (cart.length === 0) {
                cartItems.innerHTML = "<p>No items in cart.</p>";
                return;
            }

            // Otherwise, render items
            cartItems.innerHTML = "";
            cart.forEach(item => {
                const div = document.createElement("div");
                div.className = "cart-item";
                div.innerHTML = `
                    <p><strong>${item.name}</strong></p>
                    <p>Qty: ${item.qty}</p>
                    <p>Price: $${(item.price * item.qty).toFixed(2)}</p>
                `;
                cartItems.appendChild(div);
            });
        }


       // Wait for DOM to load, then attach event listeners
        document.addEventListener('DOMContentLoaded', function () {
            const buttons = document.querySelectorAll('.add-to-cart-btn');

            buttons.forEach(button => {
                button.addEventListener('click', function () {
                    const id = this.dataset.id;
                    const name = this.dataset.name;
                    const price = parseFloat(this.dataset.price);

                    const existingItem = cart.find(item => item.id === id);
                    if (existingItem) {
                        existingItem.qty += 1;
                    } else {
                        cart.push({ id, name, price, qty: 1 });
                    }

                    updateCart();
                });
            });
        });
    </script>

    <script>

    document.addEventListener('DOMContentLoaded', function () {
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
                        quantity: 1,
                        type: 'ajax'
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Product added to cart!');
                    } else {
                        alert(data.error || 'Something went wrong');
                    }
                })
                .catch(err => {
                    console.error(err);
                    alert('Error adding to cart');
                });
            });
        });
    });
    </script> -->

    <script>



    // function showToast(message, isError = false) {
    //     const toast = document.getElementById('toast');
    //     toast.textContent = message;
    //     toast.className = 'toast show' + (isError ? ' error' : '');
    //     setTimeout(() => {
    //         toast.classList.remove('show');
    //     }, 2500);
    // }

    // function updateCartCount(newCount) {
    //     const cartCount = document.getElementById("cart-count");
    //     cartCount.textContent = newCount;
    // }

    // document.addEventListener('DOMContentLoaded', function () {
    //     const buttons = document.querySelectorAll('.add-to-cart-btn');

    //     buttons.forEach(button => {
    //         button.addEventListener('click', function () {
    //             const productId = this.dataset.id;

    //             fetch("{{ route('cart.store') }}", {
    //                 method: "POST",
    //                 headers: {
    //                     "Content-Type": "application/json",
    //                     "X-CSRF-TOKEN": "{{ csrf_token() }}"
    //                 },
    //                 body: JSON.stringify({
    //                     product_id: productId,
    //                     quantity: 1
    //                 })
    //             })
    //             .then(response => response.json())
    //             .then(data => {
    //                 if (data.success) {
    //                     // Update cart count from backend (optional) or increment locally
    //                     let currentCount = parseInt(document.getElementById("cart-count").textContent) || 0;
    //                     updateCartCount(currentCount + 1);

    //                     showToast("Added to cart!");
    //                 } else {
    //                     showToast(data.error || 'Something went wrong', true);
    //                 }
    //             })
    //             .catch(err => {
    //                 console.error(err);
    //                 showToast('Error adding to cart', true);
    //             });
    //         });
    //     });
    // });
    </script>



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
