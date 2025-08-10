@extends('layouts.app')

@section('content')
<style>
    .container {
        max-width: 900px;
        margin: auto;
        padding: 20px;
    }

    .title1 {
        font-size: 26px;
        font-weight: 600;
        margin-bottom: 30px;
        color: #343a40;
    }

    .order-card {
        background-color: #fff;
        border-radius: 6px;
        border: 1px solid #dee2e6;
        padding: 20px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.03);
    }

    .order-card h4 {
        font-size: 18px;
        margin-bottom: 10px;
        color: #212529;
    }

    .order-card p {
        margin: 4px 0;
        font-size: 14px;
        color: #495057;
    }

    .order-card table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 15px;
    }

    .order-card th,
    .order-card td {
        padding: 10px;
        text-align: left;
        font-size: 14px;
        color: #343a40;
        border-bottom: 1px solid #e9ecef;
    }

    .order-card thead {
        background-color: #f8f9fa;
    }

    .order-card tr:hover {
        background-color: #f1f3f5;
    }
</style>


<div class="container">
    <h2 class="title1">My Orders</h2>

    @if($orders->isEmpty())
        <p>You have no orders yet.</p>
    @else
        @foreach($orders as $order)
            <div class="order-card" style="border:1px solid #ddd;padding:15px;margin-bottom:20px;">
                <h4>Order #{{ $order->id }} - <small>{{ ucfirst($order->status) }}</small></h4>
                <p><strong>Placed on:</strong> {{ $order->created_at->format('d M Y') }}</p>
                <p><strong>Total:</strong> ₹{{ $order->total_amount }}</p>
                <p><strong>Payment Status:</strong> {{ ucfirst($order->payment->status ?? 'N/A') }}</p>

                <table style="width:100%;border-collapse:collapse;margin-top:10px;">
                    <thead>
                        <tr style="border-bottom:1px solid #ccc;">
                            <th style="padding:8px;">Product</th>
                            <th style="padding:8px;">Vendor</th>
                            <th style="padding:8px;">Qty</th>
                            <th style="padding:8px;">Price</th>
                            <th style="padding:8px;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                            <tr style="border-bottom:1px solid #eee;">
                                <td style="padding:8px;">{{ $item->product->name }}</td>
                                <td style="padding:8px;">{{ $item->product->vendor->shop_name ?? $item->product->vendor->user->name }}</td>
                                <td style="padding:8px;">{{ $item->quantity }}</td>
                                <td style="padding:8px;">₹{{ $item->price }}</td>
                                <td style="padding:8px;">{{ ucfirst($item->status ?? 'pending') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endforeach
    @endif
</div>
@endsection
