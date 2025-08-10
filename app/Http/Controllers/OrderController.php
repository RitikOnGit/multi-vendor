<?php

namespace App\Http\Controllers;

use App\Models\OrderItem;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $vendorId = auth()->user()->vendorProfile->id;

        $orders = OrderItem::with('order.user', 'product')
            ->where('vendor_id', $vendorId)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.vendorOrders', compact('orders'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled'
        ]);

        $orderItem = OrderItem::where('vendor_id', auth()->user()->vendorProfile->id)
            ->findOrFail($id);

        $orderItem->update(['status' => $request->status]);

        return back()->with('success', 'Order status updated successfully.');
    }
}
