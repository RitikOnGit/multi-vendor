@extends('admin.master')

@section('content')
<style>
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
        background-color: #fff;
        box-shadow: 0 0 5px rgba(0,0,0,0.1);
    }

    table thead {
        background-color: #f8f9fa;
    }

    table th,
    table td {
        padding: 12px 15px;
        border: 1px solid #dee2e6;
        text-align: left;
        font-size: 14px;
    }

    table th {
        font-weight: 600;
        color: #343a40;
    }

    table td {
        color: #495057;
    }

    select {
        padding: 5px 10px;
        border-radius: 4px;
        border: 1px solid #ced4da;
        background-color: #fff;
    }

    tr:nth-child(even) {
        background-color: #f1f3f5;
    }

    tr:hover {
        background-color: #e9ecef;
    }
</style>


<div class="content-page">
            <div class="container-fluid">
<h2>Orders</h2>

@if($orders->isEmpty())
    <p>No orders found.</p>
@else
    <table>
        <thead>
            <tr>
                <th>Order #</th>
                <th>Customer</th>
                <th>Product</th>
                <th>Qty</th>
                <th>Price</th>
                <th>Status</th>
                <th>Change Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $item)
                <tr>
                    <td>{{ $item->order->id }}</td>
                    <td>{{ $item->order->user->name }}</td>
                    <td>{{ $item->product->name }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>₹{{ $item->subtotal }}</td>
                    <td>{{ ucfirst($item->status) }}</td>
                    <td>
                        <form action="{{ route('vendor.orders.update', $item->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <select name="status" onchange="this.form.submit()">
                                <option value="pending" {{ $item->status=='pending'?'selected':'' }}>Pending</option>
                                <option value="processing" {{ $item->status=='processing'?'selected':'' }}>Processing</option>
                                <option value="shipped" {{ $item->status=='shipped'?'selected':'' }}>Shipped</option>
                                <option value="delivered" {{ $item->status=='delivered'?'selected':'' }}>Delivered</option>
                                <option value="cancelled" {{ $item->status=='cancelled'?'selected':'' }}>Cancelled</option>
                            </select>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endif

</div></div>
@endsection
