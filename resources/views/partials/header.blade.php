<div class="navbar">
    <div class="navbar-left">
        <h1>MyShop</h1>
    </div>
    <div class="navbar-right">
        @auth
            <!-- If the user is authenticated, show the Sign Out button -->
            <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                @csrf
                <button type="submit" class="sign-in-btn">Log Out</button>
            </form>
        @endauth

        @guest
            <!-- If the user is not authenticated, show the Sign In button -->
            <a href="{{ route('login') }}">
                <button class="sign-in-btn">Log In</button>
            </a>
        @endguest


        <div class="cart-icon" onclick="toggleCart()">
            🛒
            <span class="cart-count" id="cart-count">0</span>
        </div>
    </div>
</div>

<script>
    function toggleCart() {
        document.getElementById("cart-sidebar").classList.toggle("active");
    }
</script>
