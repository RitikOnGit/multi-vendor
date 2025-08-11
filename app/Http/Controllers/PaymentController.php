<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Razorpay\Api\Api;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function showPaymentPage(Order $order)
    {
        // dd($order->user_id, auth()->id());
        if ($order->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }
        return view('payment.mock', compact('order'));
    }

    public function mockPay(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        // Mark payment as paid
        $payment = Payment::where('order_id', $order->id)->first();
        $payment->update([
            'status' => 'paid',
            'response' => json_encode(['mock' => true])
        ]);

        $order->update(['payment_status' => 'paid']);

        return redirect()->route('thank-you')->with('success', 'Payment successful!');
    }

    public function showPaymentPage_razor(Order $order)
{
    // Ensure session has the order IDs (set by checkoutAll or checkoutVendor)
    $orderIds = session()->get('razorpay_multi_orders', []);

    if (empty($orderIds)) {
        // If session empty, assume this is single-vendor flow where route param is the order
        $orderIds = [$order->id];
        session()->put('razorpay_multi_orders', $orderIds);
    }

    // Compute total from orders in session (multi or single)
    $totalAmount = Order::whereIn('id', $orderIds)->sum('total_amount');

    // Load orders collection for display if needed
    $orders = Order::whereIn('id', $orderIds)->with('items.product')->get();

    return view('payment.razorpay', [
        'razorpayKey'     => env('RAZORPAY_KEY'),
        'amount'          => $totalAmount,
        'orders'          => $orders,
        'order'           => $order, // always pass the bound order for backwards-compatibility
        // 'razorpayOrderId' => $razorpayOrderId, // optional if you create RZP order on server side
    ]);
}


    public function razorpaySuccess(Request $request, Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        $paymentId = $request->razorpay_payment_id;

        // Optional: Verify transaction enable later for real
        /*
        $api = new Api(config('services.razorpay.key'), config('services.razorpay.secret'));
        $payment = $api->payment->fetch($paymentId);
        Log::info('Razorpay Payment Details', $payment->toArray());
        */

        // Payment update
        $order->payment()->update([
            'status' => 'paid',
            'response' => json_encode([
                'payment_id' => $paymentId,
                // 'payment_details' => $payment->toArray() // enable later
            ])
        ]);

        $order->update(['payment_status' => 'paid']);

        return redirect()->route('thank-you')->with('success', 'Payment successful!');
    }

    public function paymentSuccess(Request $request)
{
    $orderIds = session()->get('razorpay_multi_orders', []);
    // fallback to order_ids from request if session empty
    if (empty($orderIds) && $request->has('order_ids')) {
        $orderIds = $request->input('order_ids');
    }

    if (empty($orderIds)) {
        return response()->json(['success' => false, 'message' => 'No orders found.'], 400);
    }

    foreach ($orderIds as $id) {
        $order = Order::find($id);
        if ($order) {
            $order->update(['payment_status' => 'paid']);
            if ($order->payment) {
                $order->payment->update([
                    'status' => 'paid',
                    'response' => json_encode($request->all())
                ]);
            } else {
                // create if missing
                Payment::create([
                    'order_id' => $order->id,
                    'payment_method' => 'razorpay',
                    'status' => 'paid',
                    'amount' => $order->total_amount,
                    'response' => json_encode($request->all()),
                ]);
            }
        }
    }

    // Clear session
    session()->forget('razorpay_multi_orders');

    return response()->json(['success' => true]);
}




}
