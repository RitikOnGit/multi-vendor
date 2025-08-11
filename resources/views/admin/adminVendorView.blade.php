@extends('admin.master')

@section('content')
    <div class="content-page">
        <div class="container-fluid">
            <div class="container">
            <div class="card py-3 px-4">
                <div class="vendor-details" style="border-bottom: 2px solid #ddd; padding-bottom: 30px; margin-bottom: 30px;">
                    <h2>Vendor Details</h2>
                    <div class="row">
                        <div class="col-10">
                            <div>
                                <strong>Vendor Shop Name:</strong> {{ $vendor->shop_name }} <br>
                                <strong>Vendor Owner:</strong> {{ $vendor->user->name ?? 'N/A' }} <br>
                                <strong>Email:</strong> {{ $vendor->user->email ?? 'N/A' }} <br>
                                <strong>Phone:</strong> {{ $vendor->use->phone ?? 'N/A' }} <br>
                                <strong>Address:</strong> {{ $vendor->use->address ?? 'N/A' }} <br>
                                <strong>Address:</strong> {{ $vendor->gst_number ?? 'N/A' }} <br>
                            </div>
                        </div>
                        <div class="col-2 d-flex flex-column justify-content-between">
                            <div class="top-block d-flex align-items-center justify-content-start mb-3">
                                <label class="mb-0 mx-2">Status:</label>
                                @if($vendor->is_active)
                                    <span class="btn text-success">Active</span>
                                @else
                                    <span class="btn text-danger">Inactive</span>
                                @endif
                            </div>

                            <div class="d-flex justify-content-start">
                                <label class="me-2 mx-2">Action:</label>
                                <form action="{{ route('vendors.toggleStatus', $vendor->id) }}" method="POST" style="display:inline;"
                                    onsubmit="return confirm('Are you sure you want to {{ $vendor->is_active ? 'deactivate' : 'activate' }} this vendor?');">
                                    @csrf
                                    <button type="submit" class="btn btn-sm {{ $vendor->is_active ? 'btn-danger' : 'btn-success' }}">
                                        {{ $vendor->is_active ? 'Deactivate' : 'Activate' }}
                                    </button>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>

                <h3 class="mb-2">Products by {{ $vendor->shop_name }}</h3>
                <div class="row">
                    @forelse($vendor->products as $product)
                        <div class="col-md-3" style="margin-bottom: 20px;">
                            <div class="card" style="border: 1px solid #ddd; border-radius: 8px; overflow: hidden;">
                                @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="card-img-top" style="height: 200px; object-fit: cover;">
                                @else
                                    <img src="{{ asset('images/default-product.jpg') }}" alt="Default Product Image" class="card-img-top" style="height: 200px; object-fit: cover;">
                                @endif
                                <div class="card-body">
                                    <h5 class="card-title">{{ $product->name }}</h5>
                                    <p class="card-text">₹{{ number_format($product->price, 2) }}</p>

                                </div>
                            </div>
                        </div>
                    @empty
                        <p>No products available.</p>
                    @endforelse
                </div>
            </div>
        </div>
        </div>
    </div>
@endsection
