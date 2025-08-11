<?php
namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class CartService
{

    public function addToCart(int $productId, int $quantity = 1)
    {
        $product = Product::findOrFail($productId);

        if ($quantity < 1) {
            throw new \InvalidArgumentException('Quantity must be at least 1.');
        }

        if ($quantity > $product->stock) {
            throw new \RuntimeException('Quantity exceeds available stock.');
        }

        if (!Auth::check()) {
            // Guest -> session
            $cart = session()->get('cart', []);
            $found = false;
            foreach ($cart as &$item) {
                if ($item['product_id'] == $productId) {
                    $item['quantity'] = min($product->stock, $item['quantity'] + $quantity);
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                $cart[] = ['product_id' => $productId, 'quantity' => $quantity];
            }
            session()->put('cart', $cart);
            return;
        }

        // Logged in -> DB
        $user = Auth::user();
        $cart = Cart::firstOrCreate(['user_id' => $user->id]);

        $item = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $productId)
            ->first();

        if ($item) {
            $newQty = min($product->stock, $item->quantity + $quantity);
            $item->update(['quantity' => $newQty]);
        } else {
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $productId,
                'quantity' => $quantity,
            ]);
        }
    }

    public function getCartItems()
    {
        if (!Auth::check()) {
            // Guest
            $sessionCart = session()->get('cart', []);
            return collect($sessionCart)->map(function ($it) {
                $product = Product::find($it['product_id']);
                if (!$product)
                    return null;
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->price,
                    'qty' => (int) $it['quantity'],
                ];
            })->filter()->values();
        }

        // Logged-in user
        $user = Auth::user();
        $cart = Cart::with('items.product')->where('user_id', $user->id)->first();
        if (!$cart)
            return collect();

        return $cart->items->map(function ($ci) {
            return [
                'id' => $ci->product->id,
                'name' => $ci->product->name,
                'price' => $ci->product->price,
                'qty' => $ci->quantity,
            ];
        });
    }

    public function getGroupedCart()
    {
        if (!Auth::check()) {
            // Guest session cart
            $sessionCart = session()->get('cart', []);
            $items = collect($sessionCart)->map(function ($it) {
                $product = Product::with('vendor.user')->find($it['product_id']);
                if (!$product)
                    return null;
                return (object) [
                    'product' => $product,
                    'quantity' => (int) $it['quantity'],
                    'subtotal' => $product->price * $it['quantity'],
                ];
            })->filter();

            return $this->groupItemsByVendor($items);
        }

        // Authenticated user cart
        $user = Auth::user();
        $cart = Cart::with('items.product.vendor.user')->where('user_id', $user->id)->first();
        if (!$cart)
            return collect();

        $items = $cart->items->map(function ($ci) {
            return (object) [
                'id' => $ci->id,
                'product' => $ci->product,
                'quantity' => $ci->quantity,
                'subtotal' => $ci->product->price * $ci->quantity,
            ];
        });

        return $this->groupItemsByVendor($items);
    }

    /**
     * Helper: group collection of items (objects with product & quantity) by product->vendor->id
     */
    protected function groupItemsByVendor($items)
    {
        return $items->groupBy(function ($item) {
            return $item->product->vendor->id;
        })->map(function ($group) {
            $vendor = $group->first()->product->vendor;
            $total = $group->sum('subtotal');
            return (object) [
                'vendor' => $vendor,
                'items' => $group->values(),
                'total' => $total,
            ];
        })->values();
    }

    /**
     * Update quantity (handles both guest & auth)
     */
    public function updateQuantity($identifier, int $quantity)
{
    if ($quantity < 1) {
        throw new \InvalidArgumentException('Quantity must be at least 1.');
    }

    if (!Auth::check()) {
        // Guest cart
        $cart = session()->get('cart', []);
        foreach ($cart as &$item) {
            if ($item['product_id'] == $identifier) {
                $product = Product::findOrFail($identifier);
                if ($quantity > $product->stock) {
                    throw new \RuntimeException('Quantity exceeds stock.');
                }
                $item['quantity'] = $quantity;
                break;
            }
        }
        session()->put('cart', $cart);
        return;
    }

    // Logged-in cart
    $cartItem = CartItem::findOrFail($identifier);
    $product = $cartItem->product;

    if ($quantity > $product->stock) {
        throw new \RuntimeException('Quantity exceeds stock.');
    }

    // Agar order placed ho chuka hai aur quantity kam kar rahe hain → rollback stock
    if ($cartItem->order_id && $quantity < $cartItem->quantity) {
        $diff = $cartItem->quantity - $quantity;
        $product->increment('stock', $diff);
    }

    // Agar quantity badha rahe hain → pehle stock check kare
    $cartItem->update(['quantity' => $quantity]);
}


    /**
     * Remove item (product_id for guest, cart_item id for auth)
     */
    public function removeItem($identifier)
{
    if (!Auth::check()) {
        // Guest user - sirf session se remove
        $cart = session()->get('cart', []);
        $cart = collect($cart)->reject(fn($it) => $it['product_id'] == $identifier)->values()->all();
        session()->put('cart', $cart);
        return;
    }

    $cartItem = CartItem::findOrFail($identifier);

    if ($cartItem->order_id) {
        $product = $cartItem->product;
        $product->increment('stock', $cartItem->quantity);
    }

    $cartItem->delete();
}

}
