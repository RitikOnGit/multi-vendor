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
    // public function checkoutAll(Request $request)
    // {
    //     $shipping = [
    //         'address' => 'User Address Example',
    //         'city'    => 'Mumbai',
    //         'pincode' => '400001',
    //     ];

    //     $orders = $this->checkout->checkout($shipping, 'razorpay');
    //     return redirect()->route('payment.razorpay.page', $orders->first()->id);

    // }

    public function checkoutAll(Request $request)
{
    $shipping = [
        'address' => 'User Address Example',
        'city'    => 'Mumbai',
        'pincode' => '400001',
    ];

    $orders = $this->checkout->checkout($shipping, 'razorpay'); // multi-vendor orders

    // Combined total
    $totalAmount = $orders->sum('total_amount');

    // Create Razorpay order
    $api = new \Razorpay\Api\Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));
    $razorpayOrder = $api->order->create([
        'receipt' => 'multi_vendor_' . now()->timestamp,
        'amount' => $totalAmount * 100, // in paise
        'currency' => 'INR',
    ]);

    // Store in session for later update
    session()->put('razorpay_multi_orders', $orders->pluck('id')->toArray());

    $order = $orders->first();

        return view('payment.razorpay', [
            'razorpayOrderId' => $razorpayOrder['id'],
            'amount' => $totalAmount,
            'orders' => $orders,
            'order' => $order,
            'razorpayKey' => env('RAZORPAY_KEY'),
        ]);
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

        $orders = $this->checkout->checkoutOnlyVendor($request->vendor_id, $shipping, 'razorpay');
        session()->put('razorpay_multi_orders', [$orders->first()->id]);
        return redirect()->route('payment.razorpay.page', $orders->first()->id);

    }
}
