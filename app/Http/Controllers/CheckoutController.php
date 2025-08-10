<?php

namespace App\Http\Controllers;

use App\Services\CheckoutService;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    protected $checkout;

    public function __construct(CheckoutService $checkout)
    {
        $this->checkout = $checkout;
    }

    // Full cart checkout
    public function checkoutAll(Request $request)
    {
        // Shipping details ka example — tum form se address le sakte ho
        $shipping = [
            'address' => 'User Address Example',
            'city'    => 'Mumbai',
            'pincode' => '400001',
        ];

        $orders = $this->checkout->checkout($shipping, 'cod');

        return redirect()->route('orders.thankyou')->with('orders', $orders->pluck('id')->toArray());
    }

    // Vendor-specific checkout
    public function checkoutVendor(Request $request)
    {
        $request->validate([
            'vendor_id' => 'required|exists:vendors,id',
        ]);

        $shipping = [
            'address' => 'User Address Example',
            'city'    => 'Mumbai',
            'pincode' => '400001',
        ];

        $orders = $this->checkout->checkoutOnlyVendor($request->vendor_id, $shipping, 'cod');

        return redirect()->route('orders.thankyou')->with('orders', $orders->pluck('id')->toArray());
    }
}
