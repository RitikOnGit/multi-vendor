<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

use App\Models\Cart;
use App\Models\CartItem;

class MergeCartOnLogin
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Login $event)
    {
        $sessionCart = session()->get('cart', []);
        if (empty($sessionCart)) return;

        $user = $event->user;
        $cart = Cart::firstOrCreate(['user_id' => $user->id]);

        foreach ($sessionCart as $it) {
            $productId = $it['product_id'];
            $qty = $it['quantity'];

            $existing = CartItem::where('cart_id', $cart->id)
                ->where('product_id', $productId)
                ->first();

            if ($existing) {
                $existing->quantity = min($existing->quantity + $qty, $existing->product->stock);
                $existing->save();
            } else {
                CartItem::create([
                    'cart_id' => $cart->id,
                    'product_id' => $productId,
                    'quantity' => min($qty, \App\Models\Product::find($productId)->stock),
                ]);
            }
        }

        // clear session cart
        session()->forget('cart');
    }

}
