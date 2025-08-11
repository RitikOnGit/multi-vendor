@extends('admin.master')

@section('title', 'Vendors')

@section('content')
    <div class="">
        <div class="content-page">
         <div class="container-fluid">
            <div class="row">
                @if(Auth::user() && Auth::user()->role === 'admin')
                     @foreach($vendors as $vendor)
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="card-transparent card-block card-stretch card-height">
                                <div class="card-body text-start p-0">
                                    <div class="item">
                                        <div class="odr-content rounded p-3 border">
                                            <div class="top-block d-flex align-items-center justify-content-end">
                                                @if($vendor->is_active)
                                                    <span class="badge bg-success">Active</span>
                                                @else
                                                    <span class="badge bg-danger">Inactive</span>
                                                @endif
                                            </div>
                                            <h5 class="mb-2"><strong>Name:</strong> {{ $vendor->user->name ?? 'N/A' }}</h5>
                                            <p class="mb-1"><strong>Email:</strong> {{ $vendor->user->email ?? 'N/A' }}</p>
                                            <p class="mb-1"><strong>Store Name:</strong> {{ $vendor->shop_name ?? 'N/A' }}</p>
                                            <p class="mb-0"><strong>GST Number:</strong> {{ $vendor->gst_number ?? 'N/A' }}</p>
                                            <div class="pt-3 border-top">
                                                <a class="btn btn-sm btn btn-primary" href="{{ route('admin.vendorPage', $vendor->id) }}"> View </a>
                                            </div>
                                            </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="col-md-12">
                        <div class="alert alert-warning" role="alert">
                            You do not have permission to view this dashboard.
                        </div>
                    </div>
                @endif

            </div>
            <!-- Page end  -->
        </div>
          </div>

    </div>
@endsection
