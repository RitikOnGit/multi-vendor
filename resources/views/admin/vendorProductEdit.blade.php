@extends('admin.master')

@section('title', 'Products')

@section('content')
    <div class="content-page">
        <div class="container-fluid">
            <div class="container">
                <div class="card p-4">
                    <h2>Edit Product</h2>

                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <form action="{{ route('vendor.products.update', $product->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label>Name</label>
                            <input type="text" name="name" value="{{ old('name', $product->name) }}" class="form-control"
                                required>
                        </div>

                        <div class="mb-3">
                            <label>Description</label>
                            <textarea name="description"
                                class="form-control">{{ old('description', $product->description) }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label>Price</label>
                            <input type="number" name="price" value="{{ old('price', $product->price) }}"
                                class="form-control" min="0" required>
                        </div>

                        <div class="mb-3">
                            <label>Stock</label>
                            <input type="number" name="stock" value="{{ old('stock', $product->stock) }}"
                                class="form-control" min="0" required>
                        </div>

                        <div class="mb-3">
                            <label>Current Image</label><br>
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" width="120">
                            @else
                                <p>No image uploaded.</p>
                            @endif
                        </div>

                        <div class="mb-3">
                            <label>Change Image</label>
                            <input type="file" name="image" class="form-control">
                        </div>

                        <button type="submit" class="btn btn-primary">Update Product</button>
                        <a href="{{ route('vendor.products') }}" class="btn btn-secondary">Back To Products</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
