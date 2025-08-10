<?php
namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Events\OrderPlaced;

class CheckoutService
{
    /**
     * Accepts: $user (auth user), $shipping (array), $paymentMethod (string)
     * Returns: collection of created orders
     */
    public function checkout(array $shipping, string $paymentMethod = 'cod')
{
    $user = Auth::user() ?? null;
    if (!$user) {
        throw new \RuntimeException('User must be authenticated for checkout.');
    }

    $cartService = new \App\Services\CartService();
    $groups = $cartService->getGroupedCart();

    if ($groups->isEmpty()) {
        throw new \RuntimeException('Cart is empty.');
    }

    $createdOrders = collect();

    DB::beginTransaction();
    try {
        foreach ($groups as $group) {
            $vendor = $group->vendor;
            $items = $group->items;

            $orderTotal = $items->sum('subtotal');

            // Order create
            $order = Order::create([
                'user_id' => $user->id,
                'total_amount' => $orderTotal,
                'status' => 'pending',
                'payment_status' => $paymentMethod === 'cod' ? 'pending' : 'paid',
                'shipping_address' => json_encode($shipping), // array ko json me store karo
            ]);

            foreach ($items as $it) {
                $product = Product::lockForUpdate()->find($it->product->id);
                if (!$product) throw new \RuntimeException('Product not found: ' . $it->product->id);
                if ($it->quantity > $product->stock) {
                    throw new \RuntimeException("Insufficient stock for product: {$product->name}");
                }

                $product->decrement('stock', $it->quantity);

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'vendor_id' => $product->vendor_id, // yahan product se vendor_id lo
                    'quantity' => $it->quantity,
                    'price' => $product->price,
                    'subtotal' => $product->price * $it->quantity,
                ]);
            }

            Payment::create([
                'order_id' => $order->id,
                'payment_method' => $paymentMethod,
                'status' => $paymentMethod === 'cod' ? 'pending' : 'paid',
                'amount' => $orderTotal,
                'response' => null,
            ]);

            $createdOrders->push($order);

            event(new OrderPlaced($order));
        }

        // Cart clear
        Cart::where('user_id', $user->id)->delete();

        DB::commit();
        return $createdOrders;
    } catch (\Throwable $e) {
        DB::rollBack();
        throw $e;
    }
}


    public function checkoutOnlyVendor(int $vendorId, array $shipping, string $paymentMethod = 'cod')
{
    $user = Auth::user() ?? null;
    if (!$user) {
        throw new \RuntimeException('User must be authenticated for checkout.');
    }

    $cartService = new \App\Services\CartService();
    $groups = $cartService->getGroupedCart();

    // Sirf us vendor ka group filter karo
    $vendorGroup = $groups->firstWhere('vendor.id', $vendorId);
    if (!$vendorGroup) {
        throw new \RuntimeException('No cart items found for this vendor.');
    }

    $createdOrders = collect();

    DB::beginTransaction();
    try {
        $vendor = $vendorGroup->vendor;
        $items = $vendorGroup->items;

        $orderTotal = $items->sum('subtotal');

        // Create order
        $order = Order::create([
            'user_id' => $user->id,
            'total_amount' => $orderTotal,
            'status' => 'pending',
            'payment_status' => $paymentMethod === 'cod' ? 'pending' : 'pending',
            'shipping_address' => $shipping,
        ]);

        foreach ($items as $it) {
            $product = Product::lockForUpdate()->find($it->product->id);
            if (!$product) throw new \RuntimeException('Product not found: '.$it->product->id);
            if ($it->quantity > $product->stock) {
                throw new \RuntimeException("Insufficient stock for product: {$product->name}");
            }

            // Stock decrement
            $product->decrement('stock', $it->quantity);

            // Order Item create - vendor_id from product
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'vendor_id' => $product->vendor_id, // <- yahan se vendor id li
                'quantity' => $it->quantity,
                'price' => $product->price,
                'subtotal' => $product->price * $it->quantity,
            ]);
        }

        // Payment record
        Payment::create([
            'order_id' => $order->id,
            'payment_method' => $paymentMethod,
            'status' => $paymentMethod === 'cod' ? 'pending' : 'paid',
            'amount' => $orderTotal,
            'response' => null,
        ]);

        $createdOrders->push($order);

        event(new OrderPlaced($order));

        // Sirf is vendor ke cart items remove karo (product->vendor_id check karke)
        $cart = \App\Models\Cart::where('user_id', $user->id)->first();
        if ($cart) {
            $cart->items()->whereHas('product', function ($q) use ($vendorId) {
                $q->where('vendor_id', $vendorId);
            })->delete();
        }

        DB::commit();
        return $createdOrders;

    } catch (\Throwable $e) {
        DB::rollBack();
        throw $e;
    }
}


}
