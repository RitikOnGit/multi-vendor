@extends('admin.master')

@section('title', 'Dashboard')

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
                    @foreach ($categories as $category)

                        <div class="col-lg-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="card card-widget task-card">
                                                <div class="card-body">
                                                    <div class="d-flex flex-wrap align-items-center justify-content-between">
                                                        <div class="d-flex align-items-center" style="gap: 20px;">

                                                            <img src="{{ asset('storage/' . $category->image) }}" alt="">
                                                            <h5 class="mb-2">{{ $category->title }}</h5>
                                                        </div>
                                                        <div class="media align-items-center mt-md-0 mt-3">
                                                            <a class="btn bg-secondary-light" data-toggle="collapse"
                                                                href="#collapseEdit1" role="button" aria-expanded="false"
                                                                aria-controls="collapseEdit1"><i
                                                                    class="ri-edit-box-line m-0"></i></a>
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
                        <form action="{{ route('categories.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group mb-3">
                                    <label for="categoryTitle" class="h5">Category Title</label>
                                    <input type="text" class="form-control" id="categoryTitle" name="title" placeholder="Enter category title" required>
                                    <a href="#" class="task-edit text-body"><i class="ri-edit-box-line"></i></a>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="form-group mb-0">
                                    <label for="categoryImage" class="h5">Category Image</label>
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
                        </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
