@extends('admin.master')

@section('content')
<div class="content-page">
    <div class="container-fluid">
        <div class="container">

            <h1 style="margin-bottom: 20px;">All Vendor Products</h1>

            {{-- Style the table --}}
            <style>
                table {
                    width: 100%;
                    border-collapse: collapse;
                    font-family: Arial, sans-serif;
                    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                }

                thead {
                    background-color: #343a40;
                    color: #fff;
                }

                th, td {
                    padding: 12px 15px;
                    text-align: left;
                    border-bottom: 1px solid #ddd;
                }

                tbody tr:hover {
                    background-color: #f1f1f1;
                }

                td {
                    vertical-align: middle;
                }

                .pagination {
                    margin-top: 20px;
                    display: flex;
                    justify-content: center;
                }
            </style>

            <table>
                <thead>
                    <tr>
                        <th>Sr. No.</th>
                        <th>ID</th>
                        <th>Product Name</th>
                        <th>Img</th>
                        <th>Vendor Shop</th>
                        <th>Vendor Name</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Created At</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $index => $product)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $product->id }}</td>
                            <td>{{ $product->name }}</td>
                            <td><img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" width="100"></td>
                            <td>{{ $product->vendor->shop_name ?? 'N/A' }}</td>
                            <td>{{ $product->vendor->user->name ?? 'N/A' }}</td>
                            <td>₹{{ number_format($product->price, 2) }}</td>
                            <td>{{ $product->stock }}</td>
                            <td>{{ $product->created_at->format('d M Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">No products found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="pagination">
                {{ $products->links() }}
            </div>

        </div>
    </div>
</div>
@endsection
