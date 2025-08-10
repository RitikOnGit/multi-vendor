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

        // Get grouped cart (by vendor) using your CartService logic or re-use CartService
        $cartService = new \App\Services\CartService();
        $groups = $cartService->getGroupedCart(); // grouped by vendor

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

                $order = Order::create([
                    'user_id' => $user->id,
                    'total_amount' => $orderTotal,
                    'status' => 'pending',
                    'payment_status' => $paymentMethod === 'cod' ? 'pending' : 'pending',
                    'shipping_address' => $shipping,
                ]);

                foreach ($items as $it) {
                    // $it has product and quantity
                    $product = Product::lockForUpdate()->find($it->product->id);
                    if (!$product) throw new \RuntimeException('Product not found: '.$it->product->id);
                    if ($it->quantity > $product->stock) {
                        throw new \RuntimeException("Insufficient stock for product: {$product->name}");
                    }

                    $product->decrement('stock', $it->quantity);

                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'vendor_id' => $vendor->id,
                        'quantity' => $it->quantity,
                        'price' => $product->price,
                        'subtotal' => $product->price * $it->quantity,
                    ]);
                }

                // Create a basic payment record (status pending or paid, depending)
                $payment = Payment::create([
                    'order_id' => $order->id,
                    'payment_method' => $paymentMethod,
                    'status' => $paymentMethod === 'cod' ? 'pending' : 'paid', // adapt for real gateway
                    'amount' => $orderTotal,
                    'response' => null,
                ]);

                $createdOrders->push($order);

                // Fire event
                event(new OrderPlaced($order));
            }

            // Clear user's cart (DB)
            Cart::where('user_id', $user->id)->delete();

            DB::commit();
            return $createdOrders;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
