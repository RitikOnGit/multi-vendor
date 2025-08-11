@extends('admin.master')

@section('title', 'Products')

@section('content')
    <div class="">
        <div class="content-page">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex flex-wrap align-items-center justify-content-between breadcrumb-content">
                                    <h5>Products</h5>
                                    <div class="d-flex flex-wrap align-items-center">
                                        <a href="#" class="btn btn-primary" data-target="#new-task-modal"
                                            data-toggle="modal">Add Product</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @foreach ($products as $product)

                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="card card-widget task-card">
                                                <div class="card-body">
                                                    <div class="d-flex flex-wrap align-items-center justify-content-between">
                                                        <div class="col-3">
                                                            <img src="{{ asset('storage/' . $product->image) }}" alt="" style="width: 150px;">
                                                        </div>
                                                        <div class="col-8 " style="gap: 20px;">
                                                            <h5 class="mb-2"><strong>Title:</strong> {{ $product->name }}</h5>
                                                            <p class="mb-2"><strong>Description:</strong> {{ $product->description }}</p>
                                                            <div class="d-flex" style="gap: 20px;">
                                                                <p><strong>Stock:</strong> {{ $product->stock }}</p>
                                                                <p><strong>Price:</strong> {{ $product->price }}</p>
                                                            </div>
                                                        </div>
                                                        <div class="col-1">
                                                            <div class="media align-items-center mt-md-0 mt-3">
                                                                <a href="{{ route('vendor.products.edit', $product->id) }}" class="btn bg-secondary-light">
                                                                    <i class="ri-edit-box-line m-0"></i>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>




                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                </div>
                <!-- Page end  -->
            </div>
        </div>

        <div class="modal fade bd-example-modal-lg" role="dialog" aria-modal="true" id="new-task-modal">
            <div class="modal-dialog  modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header d-block text-center pb-3 border-bttom">
                        <h3 class="modal-title" id="exampleModalCenterTitle">New Product</h3>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('vendor.product.store') }}" method="POST" enctype="multipart/form-data">@csrf
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group mb-3">
                                    <label for="categoryTitle" class="h5">Product Title</label>
                                    <input type="text" class="form-control" id="categoryTitle" name="name" placeholder="Enter product title"
                                        required>
                                    <a href="#" class="task-edit text-body"><i class="ri-edit-box-line"></i></a>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group mb-3">
                                    <label for="productDescription" class="h5">Product Description</label>
                                    <textarea class="form-control" id="productDescription" name="description" rows="4"
                                        placeholder="Enter product description"></textarea>
                                    <a href="#" class="task-edit text-body"><i class="ri-edit-box-line"></i></a>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group mb-3">
                                    <label for="productPrice" class="h5">Product Price</label>
                                    <input type="number" class="form-control" id="productPrice" name="price" placeholder="Enter product price"
                                        min="0" step="0.01" required>
                                    <a href="#" class="task-edit text-body"><i class="ri-edit-box-line"></i></a>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group mb-3">
                                    <label for="productStock" class="h5">Stock Quantity</label>
                                    <input type="number" class="form-control" id="productStock" name="stock" placeholder="Enter stock quantity"
                                        min="0" step="1" required>
                                    <a href="#" class="task-edit text-body"><i class="ri-edit-box-line"></i></a>
                                </div>
                            </div>


                            <div class="col-lg-12">
                                <div class="form-group mb-0">
                                    <label for="categoryImage" class="h5">Product Image</label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="categoryImage" name="image" required>
                                        <label class="custom-file-label" for="categoryImage">Upload media</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="d-flex flex-wrap align-items-ceter justify-content-center mt-4">
                                    <button type="submit" class="btn btn-primary mr-3">Save</button>
                                    <div class="btn btn-primary" data-dismiss="modal">Cancel</div>
                                </div>
                            </div>
                        </div></form>

                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
